1. **Fix Rate Limit Bypass Vulnerability in `app/Livewire/PostComments.php`**
   - In `app/Livewire/PostComments.php`, the `addComment()` method calls `$this->validate()` before applying the rate limiter. This allows an attacker to spam invalid payloads, bypassing the rate limiter and causing resource exhaustion (DoS) through repeated validation failures.
   - I will modify `addComment()` to check `RateLimiter::tooManyAttempts()` and calculate `IpAnonymizer::hashRequest()` *before* `$this->validate()` is called.
2. Complete pre-commit steps to ensure proper testing, verification, review, and reflection are done.
3. Submit the change with a clear commit message.
