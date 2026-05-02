#!/usr/bin/env python3
"""
Cybersec Profile Scraper
Scrapes TryHackMe and LetsDefend public profiles using Playwright + Stealth.
Outputs JSON to stdout for Laravel artisan command consumption.

Usage:
    python scrape_cybersec.py --platform tryhackme --username benidictustriwibowo
    python scrape_cybersec.py --platform letsdefend --username CrystalliXe
"""

import argparse
import json
import re
import sys
from playwright.sync_api import sync_playwright, TimeoutError as PlaywrightTimeout
from playwright_stealth import Stealth


def create_browser(playwright):
    """Create a browser context (stealth is applied at playwright level)."""
    browser = playwright.chromium.launch(
        headless=True,
        args=[
            "--disable-blink-features=AutomationControlled",
            "--no-sandbox",
            "--disable-dev-shm-usage",
        ]
    )
    context = browser.new_context(
        user_agent="Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36",
        viewport={"width": 1920, "height": 1080},
        locale="en-US",
        timezone_id="Asia/Jakarta",
    )
    return browser, context


def get_stealth_playwright():
    """Get a stealth-wrapped sync_playwright context manager."""
    return Stealth().use_sync(sync_playwright())


def scrape_tryhackme(username: str) -> dict:
    """Scrape TryHackMe public profile stats."""
    url = f"https://tryhackme.com/p/{username}"
    result = {
        "platform": "tryhackme",
        "username": username,
        "success": False,
        "error": None,
        "stats": {}
    }

    with get_stealth_playwright() as p:
        browser, context = create_browser(p)
        page = context.new_page()

        try:
            page.goto(url, wait_until="domcontentloaded", timeout=45000)
            # Wait for profile content to render
            page.wait_for_timeout(8000)

            # Check for Cloudflare challenge
            title = page.title() or ""
            current_url = page.url
            if "challenge" in current_url or "Just a moment" in title:
                # Wait longer and retry
                page.wait_for_timeout(10000)
                title = page.title() or ""
                if "Just a moment" in title:
                    result["error"] = "Cloudflare challenge detected"
                    browser.close()
                    return result

            body_text = page.inner_text("body")

            # Extract rank/top percent (e.g., "Top 20%")
            top_match = re.search(r'Top\s+(\d+)%', body_text)
            if top_match:
                result["stats"]["top_percent"] = f"Top {top_match.group(1)}%"

            # Extract badges count
            badges_match = re.search(r'Badges?\s*\n\s*(\d+)', body_text)
            if badges_match:
                result["stats"]["badges_count"] = int(badges_match.group(1))

            # Extract streak
            streak_match = re.search(r'Streak\s*\n\s*(\d+)', body_text)
            if streak_match:
                result["stats"]["streak"] = int(streak_match.group(1))

            # Extract completed rooms
            rooms_match = re.search(r'Completed\s+rooms?\s*\n\s*(\d+)', body_text, re.IGNORECASE)
            if rooms_match:
                result["stats"]["rooms_completed"] = int(rooms_match.group(1))

            # Extract rank text
            rank_match = re.search(r'Rank\s*\n\s*(.+?)(?:\n|$)', body_text)
            if rank_match:
                rank_text = rank_match.group(1).strip()
                if len(rank_text) < 50 and rank_text != result["stats"].get("top_percent"):
                    result["stats"]["rank"] = rank_text

            # Extract points
            points_match = re.search(r'(\d[\d,]+)\s*points', body_text, re.IGNORECASE)
            if not points_match:
                points_match = re.search(r'Points?\s*\n\s*(\d[\d,]*)', body_text, re.IGNORECASE)
            if points_match:
                points_str = points_match.group(1).replace(",", "")
                result["stats"]["points"] = int(points_str)

            if result["stats"]:
                result["success"] = True
            else:
                result["error"] = "Could not parse any stats from page"
                result["debug_text"] = body_text[:2000]

        except PlaywrightTimeout:
            result["error"] = "Page load timeout (45s)"
        except Exception as e:
            result["error"] = str(e)
        finally:
            browser.close()

    return result


def scrape_letsdefend(username: str) -> dict:
    """Scrape LetsDefend public profile stats."""
    url = f"https://app.letsdefend.io/user/{username}"
    result = {
        "platform": "letsdefend",
        "username": username,
        "success": False,
        "error": None,
        "stats": {}
    }

    with get_stealth_playwright() as p:
        browser, context = create_browser(p)
        page = context.new_page()

        try:
            page.goto(url, wait_until="domcontentloaded", timeout=45000)
            page.wait_for_timeout(8000)

            title = page.title() or ""
            if "Just a moment" in title:
                page.wait_for_timeout(10000)
                title = page.title() or ""
                if "Just a moment" in title:
                    result["error"] = "Cloudflare challenge detected"
                    browser.close()
                    return result

            # Special check for 404 in body text
            body_text = page.inner_text("body")
            if "404" in body_text and "can't find that page" in body_text:
                result["error"] = "Profile not found (404)"
                browser.close()
                return result

            # Extract points (e.g., "29596 Point")
            points_match = re.search(r'(\d[\d,]*)\s*Points?', body_text, re.IGNORECASE)
            if points_match:
                points_str = points_match.group(1).replace(",", "")
                result["stats"]["points"] = int(points_str)

            # Count badges
            # We can use page.locator count for more accuracy if inner_text is messy
            try:
                badge_count = page.locator(".Timeline_itemTimeline__ehBHn").count()
                if badge_count > 0:
                    result["stats"]["badges_count"] = badge_count
                else:
                    # Fallback to regex if locator fails or returns 0
                    badges_match = re.search(r'Badges?\s*:?\s*(\d+)', body_text, re.IGNORECASE)
                    if badges_match:
                        result["stats"]["badges_count"] = int(badges_match.group(1))
            except:
                pass

            # Count labs completed
            try:
                lab_count = page.locator(".BadgeKnowHow_itemsContainer__Sl3KP").count()
                if lab_count > 0:
                    result["stats"]["rooms_completed"] = lab_count
                else:
                    labs_match = re.search(r'Labs?\s*:?\s*(\d+)', body_text, re.IGNORECASE)
                    if labs_match:
                        result["stats"]["rooms_completed"] = int(labs_match.group(1))
            except:
                pass

            # Rank is often not visible on public profile, but let's try
            rank_match = re.search(r'Rank:\s*(.+?)(?:\n|$)', body_text)
            if rank_match:
                result["stats"]["rank"] = rank_match.group(1).strip()

            if result["stats"]:
                result["success"] = True
            else:
                result["error"] = "Could not parse any stats from page"
                result["debug_text"] = body_text[:2000]

        except PlaywrightTimeout:
            result["error"] = "Page load timeout (45s)"
        except Exception as e:
            result["error"] = str(e)
        finally:
            browser.close()

    return result


def main():
    parser = argparse.ArgumentParser(description="Scrape cybersec training profile stats")
    parser.add_argument("--platform", required=True, choices=["tryhackme", "letsdefend"])
    parser.add_argument("--username", required=True)
    args = parser.parse_args()

    if args.platform == "tryhackme":
        result = scrape_tryhackme(args.username)
    elif args.platform == "letsdefend":
        result = scrape_letsdefend(args.username)
    else:
        result = {"success": False, "error": f"Unknown platform: {args.platform}"}

    print(json.dumps(result, indent=2))
    sys.exit(0 if result.get("success") else 1)


if __name__ == "__main__":
    main()
