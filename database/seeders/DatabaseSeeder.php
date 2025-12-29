<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use App\Models\Experience;
use App\Models\Skill;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@portfolio.local',
            'password' => bcrypt('password'),
        ]);

        // Seed Experiences (CV Data)
        Experience::create([
            'company' => 'Maybank Indonesia Finance',
            'role' => 'ICT Risk Staff',
            'type' => 'GRC',
            'date_range' => '2023 - Present',
            'description' => 'Managing ICT risk assessments, security control implementation, and compliance with ISO 27001 standards. Conducting vulnerability assessments and coordinating with IT teams for risk mitigation.',
            'sort_order' => 1,
        ]);

        Experience::create([
            'company' => 'CIMB Niaga',
            'role' => 'Quality Assurance Engineer',
            'type' => 'QA',
            'date_range' => '2021 - 2023',
            'description' => 'Performed comprehensive testing for banking applications, including security testing, API testing, and automated test script development. Ensured compliance with security standards.',
            'sort_order' => 2,
        ]);

        // Seed Projects (Home Lab)
        Project::create([
            'title' => 'Proxmox Virtualization Server',
            'description' => 'Self-hosted Proxmox VE cluster running on Ryzen hardware. Hosts multiple VMs including security tools, development environments, and home automation systems.',
            'status' => 'online',
            'type' => 'Home Lab',
            'tech_stack' => ['Proxmox VE', 'Ryzen 5600X', 'Ubuntu Server', 'ZFS', 'Tailscale'],
            'url' => null,
        ]);

        Project::create([
            'title' => 'Wazuh SIEM & Attack Simulation',
            'description' => 'Security monitoring stack with Wazuh SIEM integrated with Kali Linux for attack simulation and detection rule testing. Monitors all home lab infrastructure.',
            'status' => 'online',
            'type' => 'Home Lab',
            'tech_stack' => ['Wazuh', 'Kali Linux', 'Elasticsearch', 'Kibana', 'MITRE ATT&CK'],
            'url' => null,
        ]);

        Project::create([
            'title' => 'Python Financial Bot',
            'description' => 'Dockerized financial automation bot for tracking investments and generating reports. Integrates with various financial APIs for real-time data.',
            'status' => 'online',
            'type' => 'Script',
            'tech_stack' => ['Python', 'Docker', 'PostgreSQL', 'REST APIs', 'Telegram Bot API'],
            'url' => null,
        ]);

        Project::create([
            'title' => 'Tailscale Mesh Network',
            'description' => 'Secure mesh VPN connecting all home lab infrastructure with encrypted WireGuard tunnels. Enables secure remote access to all services.',
            'status' => 'online',
            'type' => 'Home Lab',
            'tech_stack' => ['Tailscale', 'WireGuard', 'DNS', 'ACL'],
            'url' => null,
        ]);

        // Seed Skills
        Skill::create(['name' => 'ISO 27001', 'category' => 'GRC', 'level' => 90, 'icon' => 'shield-check', 'sort_order' => 1]);
        Skill::create(['name' => 'Risk Assessment', 'category' => 'GRC', 'level' => 85, 'icon' => 'alert-triangle', 'sort_order' => 2]);
        Skill::create(['name' => 'Security Policies', 'category' => 'GRC', 'level' => 85, 'icon' => 'file-text', 'sort_order' => 3]);
        Skill::create(['name' => 'Vendor Management', 'category' => 'GRC', 'level' => 80, 'icon' => 'users', 'sort_order' => 4]);
        
        Skill::create(['name' => 'Penetration Testing', 'category' => 'Technical', 'level' => 75, 'icon' => 'target', 'sort_order' => 1]);
        Skill::create(['name' => 'SIEM (Wazuh)', 'category' => 'Technical', 'level' => 80, 'icon' => 'activity', 'sort_order' => 2]);
        Skill::create(['name' => 'Python', 'category' => 'Technical', 'level' => 70, 'icon' => 'code', 'sort_order' => 3]);
        Skill::create(['name' => 'Linux Administration', 'category' => 'Technical', 'level' => 75, 'icon' => 'terminal', 'sort_order' => 4]);
        
        Skill::create(['name' => 'Docker', 'category' => 'Tools', 'level' => 80, 'icon' => 'box', 'sort_order' => 1]);
        Skill::create(['name' => 'Proxmox VE', 'category' => 'Tools', 'level' => 85, 'icon' => 'server', 'sort_order' => 2]);
        Skill::create(['name' => 'Git', 'category' => 'Tools', 'level' => 75, 'icon' => 'git-branch', 'sort_order' => 3]);
        Skill::create(['name' => 'Tailscale', 'category' => 'Tools', 'level' => 85, 'icon' => 'network', 'sort_order' => 4]);
    }
}
