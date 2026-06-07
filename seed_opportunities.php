<?php
/**
 * seed_opportunities.php
 *
 * Populates the Lotoks database with realistic job and scholarship
 * opportunity listings for African professionals and students.
 *
 * ── WHAT IT DOES ─────────────────────────────────────────────────
 * 1. Creates `job_listings` and `scholarship_listings` tables (if not exist)
 * 2. Seeds 15 job placements across Europe
 * 3. Seeds 10 scholarship opportunities at European institutions
 * 4. Also seeds the existing `listings` table so the current
 *    homepage, dashboard, and admin panel show data immediately.
 *
 * ── SAFETY ───────────────────────────────────────────────────────
 * Safe to run multiple times — skips if the new tables already
 * have data. To force a full re-seed, run the TRUNCATE statements
 * shown in the warning message.
 *
 * ── USAGE ────────────────────────────────────────────────────────
 * 1. Upload this file to the project root (htdocs/lotoks/)
 * 2. Visit: https://yoursite.com/seed_opportunities.php
 * 3. After the green success screen, DELETE THIS FILE.
 *
 * ── DEPENDENCIES ────────────────────────────────────────────────
 * Requires db/connect.php (PDO connection to MySQL)
 * Requires an existing `listings` table (from schema.sql)
 */

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/db/connect.php';

$db = getDb();

// ─────────────────────────────────────────────────────────────────
// 1. CREATE TABLES (if not exist)
// ─────────────────────────────────────────────────────────────────
$db->exec("
    CREATE TABLE IF NOT EXISTS job_listings (
      id            INT AUTO_INCREMENT PRIMARY KEY,
      title         VARCHAR(255) NOT NULL,
      description   TEXT NOT NULL,
      country       VARCHAR(100) NOT NULL,
      salary_min    VARCHAR(100) DEFAULT NULL,
      salary_max    VARCHAR(100) DEFAULT NULL,
      requirements  TEXT DEFAULT NULL,
      is_active     TINYINT(1) DEFAULT 1,
      created_by    INT DEFAULT NULL,
      created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");

$db->exec("
    CREATE TABLE IF NOT EXISTS scholarship_listings (
      id            INT AUTO_INCREMENT PRIMARY KEY,
      title         VARCHAR(255) NOT NULL,
      description   TEXT NOT NULL,
      country       VARCHAR(100) NOT NULL,
      institution   VARCHAR(255) DEFAULT NULL,
      coverage      VARCHAR(255) DEFAULT NULL,
      degree_level  VARCHAR(100) DEFAULT NULL,
      eligibility   TEXT DEFAULT NULL,
      is_active     TINYINT(1) DEFAULT 1,
      created_by    INT DEFAULT NULL,
      created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");

// ─────────────────────────────────────────────────────────────────
// 2. CHECK IF ALREADY SEEDED
// ─────────────────────────────────────────────────────────────────
$jobCount = (int)$db->query("SELECT COUNT(*) FROM job_listings")->fetchColumn();
$scholCount = (int)$db->query("SELECT COUNT(*) FROM scholarship_listings")->fetchColumn();

if ($jobCount > 0 || $scholCount > 0) {
    $totalNew = $jobCount + $scholCount;
    echo "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'><meta name='viewport' content='width=device-width,initial-scale=1.0'>"
       . "<title>Already Seeded – Lotoks</title>"
       . "<style>body{font-family:'Plus Jakarta Sans',system-ui,sans-serif;background:#f4f6fa;display:flex;justify-content:center;align-items:center;min-height:100vh;margin:0;padding:2rem;}
            .card{background:#fff;max-width:560px;width:100%;border-radius:1.25rem;padding:2.5rem;box-shadow:0 20px 40px rgba(0,0,0,.08);text-align:center;}
            h1{color:#0B1D3A;font-weight:800;margin:0 0 .5rem;font-size:1.6rem;}
            .info{background:#f0f7ff;color:#1e40af;padding:.75rem;border-radius:.5rem;font-size:.85rem;margin:1rem 0;}
            code{background:#e5e7eb;padding:.15rem .4rem;border-radius:.25rem;font-size:.8rem;}
            .btn{display:inline-block;background:#0B1D3A;color:#fff;text-decoration:none;padding:.75rem 1.5rem;border-radius:.5rem;font-weight:700;font-size:.875rem;margin-top:1rem;}
            .btn:hover{background:#132f52;}
         </style></head><body><div class='card'>"
       . "<h1>✓ Already Populated</h1>"
       . "<p style='color:#6b7280;'>The new tables already contain <strong>{$totalNew}</strong> entries.</p>"
       . "<div class='info'><strong>To re-seed:</strong> Run these SQL commands first:<br>"
       . "<code>TRUNCATE TABLE job_listings;</code><br>"
       . "<code>TRUNCATE TABLE scholarship_listings;</code><br>"
       . "<code>TRUNCATE TABLE listings;</code> (optional — if you want to refresh the old table too)</div>"
       . "<a href='" . htmlspecialchars((defined('BASE') ? BASE : '/')) . "/' class='btn'>Go to Homepage</a>"
       . "</div></body></html>";
    exit;
}

// ─────────────────────────────────────────────────────────────────
// 3. JOB LISTINGS — 15 entries
// ─────────────────────────────────────────────────────────────────
$jobs = [

    // ── IT / Software ──────────────────────────────────────────
    [
        'title'        => 'Senior Java Developer – Berlin',
        'description'  => 'Join TechSphere GmbH, a leading SaaS company in Berlin building cloud-based enterprise solutions. You will design and maintain microservices using Java 17+, Spring Boot, and Kafka. Full visa sponsorship (EU Blue Card) is offered for qualified non-EU candidates.',
        'country'      => 'Germany',
        'salary_min'   => '€65,000',
        'salary_max'   => '€85,000',
        'requirements' => "• 5+ years Java experience\n• Strong Spring Boot & microservices knowledge\n• Experience with Kafka or similar message brokers\n• English C1 required; German B2 is a plus\n• Bachelor's in Computer Science or equivalent",
    ],
    [
        'title'        => 'Full-Stack Developer (PHP/React) – Paris',
        'description'  => 'Build modern web applications for Digital Future SAS, a fast-growing French fintech startup. You will work with PHP (Symfony/Laravel) and React on a platform serving thousands of users across Europe. Passeport talent visa sponsorship available.',
        'country'      => 'France',
        'salary_min'   => '€50,000',
        'salary_max'   => '€65,000',
        'requirements' => "• 3+ years PHP experience (Symfony or Laravel)\n• React & TypeScript proficiency\n• Experience with REST APIs and Postgres\n• English B2+; French A2 is a plus\n• Degree in Computer Science or equivalent",
    ],
    [
        'title'        => 'Data Scientist – Stockholm',
        'description'  => 'Join Nordic AI Labs AB, a Stockholm-based AI research lab working on NLP and recommendation systems. Creative environment with equity packages and full relocation support for international hires.',
        'country'      => 'Sweden',
        'salary_min'   => 'SEK 45,000/mo',
        'salary_max'   => 'SEK 60,000/mo',
        'requirements' => "• MSc/PhD in Data Science, ML, or related field\n• Python (pandas, scikit-learn, PyTorch) expertise\n• Experience deploying ML models to production\n• English C1 required; Swedish is optional\n• Published research is a strong plus",
    ],
    [
        'title'        => 'Cloud Solutions Architect – Amsterdam',
        'description'  => 'Design and implement cloud infrastructure for enterprise clients across Europe at EuroCloud Consultancy. Hybrid working, professional development budget, and visa sponsorship for skilled non-EU professionals.',
        'country'      => 'Netherlands',
        'salary_min'   => '€70,000',
        'salary_max'   => '€95,000',
        'requirements' => "• AWS Solutions Architect (Professional) or Azure equivalent\n• 7+ years IT infrastructure experience\n• Terraform and Kubernetes expertise\n• Experience with CI/CD pipelines\n• English C1 required; Dutch not required",
    ],
    [
        'title'        => 'DevOps Engineer – Copenhagen',
        'description'  => 'Manage and improve CI/CD pipelines, cloud infrastructure, and monitoring for NordCloud Services, a Danish SaaS scale-up. Fast-track visa scheme for IT specialists available.',
        'country'      => 'Denmark',
        'salary_min'   => 'DKK 50,000/mo',
        'salary_max'   => 'DKK 65,000/mo',
        'requirements' => "• 3+ years DevOps experience\n• AWS or Azure certification\n• Docker, Kubernetes, Terraform skills\n• Scripting (Python or Bash)\n• English C1 required; Danish is a plus",
    ],

    // ── Engineering ────────────────────────────────────────────
    [
        'title'        => 'Electrical Engineer – Rotterdam',
        'description'  => 'Work on cutting-edge offshore wind and port electrification projects at Port Energy Solutions B.V. Skilled electrical engineer for design and implementation. Highly skilled migrant visa sponsorship available.',
        'country'      => 'Netherlands',
        'salary_min'   => '€50,000',
        'salary_max'   => '€68,000',
        'requirements' => "• BSc/MSc in Electrical Engineering\n• 3+ years in power systems or renewables\n• AutoCAD Electrical or EPLAN experience\n• English C1; Dutch is a plus\n• EU Blue Card eligibility",
    ],
    [
        'title'        => 'Mechanical Engineer – Munich',
        'description'  => 'Design next-generation automotive components for AutoTech Innovations GmbH at a top German car manufacturer\'s R&D centre in Munich. EU Blue Card sponsorship offered.',
        'country'      => 'Germany',
        'salary_min'   => '€58,000',
        'salary_max'   => '€75,000',
        'requirements' => "• BSc/MSc in Mechanical Engineering\n• 3+ years in automotive or manufacturing\n• SolidWorks / CATIA proficiency\n• English C1; German B2 preferred\n• Knowledge of ISO/TS 16949 standards",
    ],

    // ── Healthcare ─────────────────────────────────────────────
    [
        'title'        => 'Registered Nurse (ICU) – London',
        'description'  => 'The NHS London Trust is hiring experienced ICU nurses for a major London hospital. Full Health and Care Visa sponsorship, relocation support, and a comprehensive orientation programme.',
        'country'      => 'United Kingdom',
        'salary_min'   => '£32,000',
        'salary_max'   => '£45,000',
        'requirements' => "• Valid nursing degree/diploma\n• Minimum 2 years ICU experience\n• NMC registration or willingness to obtain\n• IELTS 7.0 (OET B accepted)\n• Ability to work rotating shifts",
    ],
    [
        'title'        => 'General Practitioner – Dublin',
        'description'  => 'The Irish Health Service Executive (HSE) seeks General Practitioners for community healthcare centres in the Midlands region. Full visa sponsorship and relocation package provided.',
        'country'      => 'Ireland',
        'salary_min'   => '€75,000',
        'salary_max'   => '€105,000',
        'requirements' => "• Medical degree recognised by the Irish Medical Council\n• 3+ years post-qualification experience\n• Full registration with IMC or eligibility\n• IELTS 7.5 or OET B in all domains\n• Previous GP experience preferred",
    ],

    // ── Skilled Trades ─────────────────────────────────────────
    [
        'title'        => 'Welder / Fabricator – Gdansk',
        'description'  => 'Opportunity for skilled welders and metal fabricators to join Baltic Shipyard Group, a premier shipyard in Gdansk, Poland. Work permit sponsorship plus competitive benefits.',
        'country'      => 'Poland',
        'salary_min'   => 'PLN 6,000/mo',
        'salary_max'   => 'PLN 9,000/mo',
        'requirements' => "• Certified welding qualification (MIG/TIG/ARC)\n• 2+ years industrial experience\n• Ability to read technical drawings\n• Basic English or Polish communication\n• Physical fitness for heavy industrial work",
    ],

    // ── Finance ────────────────────────────────────────────────
    [
        'title'        => 'Accountant (IFRS) – Frankfurt',
        'description'  => 'Join the finance team of Finance First GmbH, a mid-sized German corporation. Handle financial reporting, tax compliance, and audit preparation. Blue Card sponsorship available.',
        'country'      => 'Germany',
        'salary_min'   => '€48,000',
        'salary_max'   => '€62,000',
        'requirements' => "• Bachelor's in Accounting or Finance\n• ACCA / CPA / CIMA qualification preferred\n• 3+ years IFRS reporting experience\n• Advanced Excel skills; SAP experience a plus\n• English C1; German B1 required",
    ],

    // ── Hospitality ────────────────────────────────────────────
    [
        'title'        => 'Hotel Manager – Lisbon',
        'description'  => 'Manage a boutique luxury hotel in the heart of Lisbon for Heritage Hospitality Group. Lead operations, guest experience, and team management. Work visa sponsorship available.',
        'country'      => 'Portugal',
        'salary_min'   => '€35,000',
        'salary_max'   => '€48,000',
        'requirements' => "• Degree in Hospitality Management or equivalent\n• 5+ years hotel management experience\n• Strong financial and P&L management skills\n• English C1; Portuguese B1 preferred\n• Experience with PMS systems (Opera, Mews)",
    ],

    // ── Sales / Business ───────────────────────────────────────
    [
        'title'        => 'Sales Manager (FinTech) – Warsaw',
        'description'  => 'Drive B2B sales across Central and Eastern Europe for NeoPay Poland, an innovative payment platform. International candidates welcome — work permit sponsorship included.',
        'country'      => 'Poland',
        'salary_min'   => 'PLN 12,000/mo',
        'salary_max'   => 'PLN 18,000/mo + commission',
        'requirements' => "• 5+ years B2B sales experience (fintech preferred)\n• Proven track record of exceeding targets\n• English C1 + Polish or Russian is an advantage\n• CRM proficiency (Salesforce, HubSpot)\n• Willingness to travel 40%",
    ],
    [
        'title'        => 'Supply Chain Manager – Rotterdam',
        'description'  => 'Oversee end-to-end supply chain operations for Global Logistics Partners B.V., a multinational logistics firm based in the port of Rotterdam. Highly skilled migrant visa sponsorship available.',
        'country'      => 'Netherlands',
        'salary_min'   => '€55,000',
        'salary_max'   => '€72,000',
        'requirements' => "• Bachelor's in Supply Chain / Logistics\n• 5+ years in supply chain management\n• ERP system expertise (SAP/Oracle)\n• APICS/CSCP certification is a plus\n• English C1; Dutch B1 preferred",
    ],

    // ── Creative / UX ──────────────────────────────────────────
    [
        'title'        => 'UX/UI Designer – Remote, Paris HQ',
        'description'  => 'Design beautiful, accessible interfaces for PixelPerfect Studio, a French B2B software company. Remote-first with option to visit the Paris office. Passeport talent visa for those relocating to France.',
        'country'      => 'France',
        'salary_min'   => '€45,000',
        'salary_max'   => '€60,000',
        'requirements' => "• 4+ years UX/UI design experience\n• Figma mastery and design system experience\n• User research and usability testing skills\n• English C1; French B1 preferred\n• Portfolio showcasing mobile and web apps",
    ],
];

// ─────────────────────────────────────────────────────────────────
// 4. SCHOLARSHIP LISTINGS — 10 entries
// ─────────────────────────────────────────────────────────────────
$scholarships = [

    // ── Germany ────────────────────────────────────────────────
    [
        'title'        => 'DAAD Master’s Scholarship – Germany',
        'description'  => 'Fully funded Master\'s degree at participating German universities through the DAAD (German Academic Exchange Service). Open to graduates from African countries with a strong academic record. Covers tuition, monthly stipend, health insurance, and travel allowance.',
        'country'      => 'Germany',
        'institution'  => 'DAAD (German Academic Exchange Service)',
        'coverage'     => 'Full scholarship (tuition + €934/month stipend + insurance + travel)',
        'degree_level' => "Master's",
        'eligibility'  => "• Bachelor's degree with CGPA ≥ 3.0\n• English B2 or German B1 (depending on programme)\n• At least 2 years professional experience (for most programmes)\n• IELTS 6.5 or equivalent\n• Strong motivation statement",
    ],
    [
        'title'        => 'ETH Zurich Excellence Scholarship – Switzerland',
        'description'  => 'The Excellence & Opportunity Programme supports outstanding Master\'s students with full tuition coverage and living cost stipends. Priority for students from Africa and Latin America.',
        'country'      => 'Switzerland',
        'institution'  => 'ETH Zurich',
        'coverage'     => 'Full tuition + CHF 1,600/month',
        'degree_level' => "Master's",
        'eligibility'  => "• Excellent Bachelor's degree in STEM\n• IELTS 7.0 / TOEFL 100\n• GRE General Test (recommended)\n• Research experience or publications\n• Two recommendation letters",
    ],

    // ── France ─────────────────────────────────────────────────
    [
        'title'        => "Master's in Data Science – Université Paris-Saclay",
        'description'  => 'A 2-year intensive Master\'s programme in Data Science with a special track for African students. Partial and full scholarships available based on merit.',
        'country'      => 'France',
        'institution'  => 'Université Paris-Saclay',
        'coverage'     => 'Full tuition + €1,000/month stipend',
        'degree_level' => "Master's",
        'eligibility'  => "• Bachelor's in Computer Science, Maths, or Statistics\n• CGPA ≥ 3.5 (or equivalent)\n• IELTS 6.5 / TOEFL 90\n• Strong programming background (Python/R)\n• French A2 recommended",
    ],

    // ── Sweden ─────────────────────────────────────────────────
    [
        'title'        => 'Karolinska Institutet Global Master’s – Sweden',
        'description'  => 'World-leading medical university offering Master\'s programmes in public health, biomedicine, and global health. Scholarships available for outstanding students from low- and middle-income countries.',
        'country'      => 'Sweden',
        'institution'  => 'Karolinska Institutet',
        'coverage'     => 'Tuition waiver + SEK 12,000/month living costs',
        'degree_level' => "Master's",
        'eligibility'  => "• Bachelor's in Medicine, Biology, or Public Health\n• CGPA ≥ 3.3\n• IELTS 7.0 / TOEFL 100\n• Research experience preferred\n• Two academic references",
    ],
    [
        'title'        => 'Chalmers IPOET Scholarship – Sweden',
        'description'  => 'The IPOET scholarship covers 75% of the tuition fee for outstanding Master\'s students from non-EU/EEA countries at Chalmers University of Technology in Gothenburg.',
        'country'      => 'Sweden',
        'institution'  => 'Chalmers University of Technology',
        'coverage'     => '75% tuition fee waiver',
        'degree_level' => "Master's",
        'eligibility'  => "• Bachelor's in Engineering or Technology\n• Strong academic record\n• IELTS 6.5 / TOEFL 90\n• Admitted to a Chalmers Master's programme\n• Priority given to early applicants",
    ],

    // ── Netherlands ────────────────────────────────────────────
    [
        'title'        => 'University of Amsterdam Merit Scholarship',
        'description'  => 'Highly competitive merit scholarships for international students from outside the EEA. Covers tuition and living expenses for selected Master\'s programmes.',
        'country'      => 'Netherlands',
        'institution'  => 'University of Amsterdam (UvA)',
        'coverage'     => '€18,000 – €25,000/year (tuition + living)',
        'degree_level' => "Master's",
        'eligibility'  => "• Bachelor's degree with excellent grades (top 10%)\n• IELTS 7.0 or TOEFL 100\n• Strong academic references\n• Compelling motivation letter\n• Admission to a UvA Master's programme",
    ],
    [
        'title'        => 'Radboud University Scholarship – Netherlands',
        'description'  => 'The Radboud Scholarship Programme offers talented non-EU/EEA students the opportunity to pursue a Master\'s degree with a full or partial scholarship.',
        'country'      => 'Netherlands',
        'institution'  => 'Radboud University',
        'coverage'     => '€12,000 – €18,000/year (partial); full available',
        'degree_level' => "Master's",
        'eligibility'  => "• Bachelor's with excellent grades\n• IELTS 7.0 / TOEFL 100\n• Motivation letter\n• Two academic references\n• Admission to a Radboud Master's programme",
    ],

    // ── Belgium ────────────────────────────────────────────────
    [
        'title'        => 'KU Leuven Master’s Scholarship – Belgium',
        'description'  => 'KU Leuven offers partial and full scholarships for Master\'s programmes in engineering, science, and social sciences to talented students from developing countries.',
        'country'      => 'Belgium',
        'institution'  => 'KU Leuven',
        'coverage'     => '€8,000 – €15,000/year partial; full available',
        'degree_level' => "Master's",
        'eligibility'  => "• Bachelor's in relevant discipline\n• CGPA ≥ 3.2\n• English C1 (IELTS 7.0)\n• Financial need statement\n• Application to a KU Leuven Master's programme",
    ],

    // ── Italy ──────────────────────────────────────────────────
    [
        'title'        => 'University of Bologna Study Grant – Italy',
        'description'  => 'The "Unibo Action 2" study grants for international students enrolling in Bachelor\'s or Master\'s degree programmes. Grants are based on SAT/GRE scores and financial need.',
        'country'      => 'Italy',
        'institution'  => 'University of Bologna',
        'coverage'     => '€11,000/year + tuition waiver',
        'degree_level' => "Bachelor's / Master's",
        'eligibility'  => "• High school diploma (Bachelor's) or Bachelor's (Master's)\n• SAT ≥ 1300 or GRE ≥ 310\n• English B2 (IELTS 6.0)\n• Financial need documentation\n• Valid passport",
    ],

    // ── Multi-country ──────────────────────────────────────────
    [
        'title'        => 'Erasmus Mundus Joint Master – Europe',
        'description'  => 'Study at two or more European universities across different countries with a full Erasmus Mundus scholarship. Covers full tuition, monthly living allowance, travel, and insurance. Various programmes in engineering, science, and social sciences.',
        'country'      => 'Multiple (EU)',
        'institution'  => 'European Commission',
        'coverage'     => 'Full scholarship (tuition + €1,400/month + travel + insurance)',
        'degree_level' => "Master's",
        'eligibility'  => "• Bachelor's degree in relevant field\n• English C1 (IELTS 7.0 / TOEFL 95)\n• Strong academic record\n• Motivation letter and references\n• Applicants from all nationalities welcome",
    ],
];

// ─────────────────────────────────────────────────────────────────
// 5. INSERT DATA INTO NEW TABLES
// ─────────────────────────────────────────────────────────────────
$db->beginTransaction();
try {

    // Insert jobs
    $jobStmt = $db->prepare(
        "INSERT INTO job_listings (title, description, country, salary_min, salary_max, requirements, is_active, created_by)
         VALUES (?, ?, ?, ?, ?, ?, 1, 1)"
    );
    foreach ($jobs as $j) {
        $jobStmt->execute([
            $j['title'],
            $j['description'],
            $j['country'],
            $j['salary_min'],
            $j['salary_max'],
            $j['requirements'],
        ]);
    }
    $insertedJobs = count($jobs);

    // Insert scholarships
    $scholStmt = $db->prepare(
        "INSERT INTO scholarship_listings (title, description, country, institution, coverage, degree_level, eligibility, is_active, created_by)
         VALUES (?, ?, ?, ?, ?, ?, ?, 1, 1)"
    );
    foreach ($scholarships as $s) {
        $scholStmt->execute([
            $s['title'],
            $s['description'],
            $s['country'],
            $s['institution'],
            $s['coverage'],
            $s['degree_level'],
            $s['eligibility'],
        ]);
    }
    $insertedSchols = count($scholarships);

    $db->commit();
} catch (Exception $e) {
    $db->rollBack();
    http_response_code(500);
    echo "<p style='font-family:sans-serif;color:#dc2626;font-weight:700;'>✗ Database error: "
       . htmlspecialchars($e->getMessage()) . "</p>";
    exit;
}

// ─────────────────────────────────────────────────────────────────
// 6. SEED EXISTING `listings` TABLE (backward compatibility)
// ─────────────────────────────────────────────────────────────────
$existingListingsCount = (int)$db->query("SELECT COUNT(*) FROM listings")->fetchColumn();

if ($existingListingsCount === 0) {
    try {
        $db->beginTransaction();

        $legacyStmt = $db->prepare(
            "INSERT INTO listings (title, employer, description, country, sponsorship_type, salary_range, requirements, active, applicants, type)
             VALUES (?, ?, ?, ?, ?, ?, ?, 1, 0, ?)"
        );

        // Insert jobs into legacy table
        foreach ($jobs as $j) {
            $legacyStmt->execute([
                $j['title'],
                '',  // employer — not in new schema
                $j['description'],
                $j['country'],
                'job',
                ($j['salary_min'] && $j['salary_max'])
                    ? $j['salary_min'] . ' – ' . $j['salary_max']
                    : ($j['salary_min'] ?: 'Competitive'),
                $j['requirements'],
                'job',
            ]);
        }

        // Insert scholarships into legacy table
        foreach ($scholarships as $s) {
            $legacyStmt->execute([
                $s['title'],
                $s['institution'] ?? '',
                $s['description'],
                $s['country'],
                'edu',
                $s['coverage'] ?? 'Full scholarship',
                $s['eligibility'] ?? '',
                'edu',
            ]);
        }

        $db->commit();
        $legacyInserted = true;
    } catch (Exception $e) {
        $db->rollBack();
        $legacyInserted = false;
    }
} else {
    $legacyInserted = 'skipped';
}

// ─────────────────────────────────────────────────────────────────
// 7. SUCCESS OUTPUT
// ─────────────────────────────────────────────────────────────────
$totalNew = $insertedJobs + $insertedSchols;

echo "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'>"
   . "<meta name='viewport' content='width=device-width, initial-scale=1.0'>"
   . "<title>Seed Complete – Lotoks</title>"
   . "<style>
        * { box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; background: #f4f6fa; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; padding: 2rem; }
        .card { background: white; max-width: 580px; width: 100%; border-radius: 1.25rem; padding: 2.5rem; box-shadow: 0 20px 40px rgba(0,0,0,0.08); text-align: center; }
        h1 { color: #0B1D3A; font-weight: 800; margin: 0 0 0.5rem; font-size: 1.7rem; }
        .subtitle { color: #6b7280; margin-bottom: 1.5rem; line-height: 1.5; }
        .stats { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin: 1.5rem 0; }
        .stat { background: #f8fafc; border-radius: 0.75rem; padding: 1rem; }
        .stat:first-child { border-left: 4px solid #3b82f6; }
        .stat:last-child { border-left: 4px solid #8b5cf6; }
        .stat-value { font-size: 2rem; font-weight: 800; color: #0B1D3A; line-height: 1.2; }
        .stat-label { font-size: 0.78rem; color: #6b7280; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; }
        .tables-list { background: #f8fafc; border-radius: 0.75rem; padding: 1rem 1.25rem; margin-bottom: 1.5rem; text-align: left; }
        .tables-list li { font-size: 0.85rem; padding: 0.2rem 0; color: #374151; }
        .tables-list code { background: #e5e7eb; padding: 0.1rem 0.35rem; border-radius: 0.25rem; font-size: 0.78rem; }
        .legacy-note { background: #f0fdf4; color: #166534; padding: 0.75rem; border-radius: 0.5rem; font-size: 0.8rem; margin-top: 1rem; }
        .legacy-note.warn { background: #fef3c7; color: #92400e; }
        .btn { display: inline-block; background: #0B1D3A; color: white; text-decoration: none; padding: 0.75rem 1.5rem; border-radius: 0.5rem; font-weight: 700; font-size: 0.875rem; margin-top: 1rem; transition: background .2s; }
        .btn:hover { background: #132f52; }
        .btn-secondary { background: #6b7280; }
        .btn-secondary:hover { background: #4b5563; }
        .danger { background: #fef2f2; color: #991b1b; padding: 0.75rem; border-radius: 0.5rem; font-size: 0.85rem; margin-top: 1.5rem; border: 1px solid #fca5a5; }
        code.block { display: block; background: #1f2937; color: #e5e7eb; padding: 0.5rem 0.75rem; border-radius: 0.375rem; font-size: 0.75rem; margin: 0.25rem 0; text-align: left; }
     </style></head><body><div class='card'>"
   . "<h1>✓ Database Seeded!</h1>"
   . "<p class='subtitle'>The Lotoks database now contains realistic job and scholarship listings. Your homepage will display live opportunities immediately.</p>"

   . "<div class='stats'>"
   .   "<div class='stat'><div class='stat-value'>{$insertedJobs}</div><div class='stat-label'>Job Listings</div></div>"
   .   "<div class='stat'><div class='stat-value'>{$insertedSchols}</div><div class='stat-label'>Scholarships</div></div>"
   . "</div>"

   . "<p style='font-weight:700;color:#0B1D3A;font-size:1.1rem;'>Total: {$totalNew} opportunities</p>"

   . "<div class='tables-list'>"
   .   "<p style='font-weight:700;margin:0 0 0.5rem;font-size:0.85rem;color:#0B1D3A;'>Tables populated:</p>"
   .   "<ul style='margin:0;padding-left:1.25rem;'>"
   .     "<li><code>job_listings</code> — {$insertedJobs} rows (new table)</li>"
   .     "<li><code>scholarship_listings</code> — {$insertedSchols} rows (new table)</li>";

if ($legacyInserted === true) {
    echo "     <li><code>listings</code> — {$totalNew} rows (existing table, for backward compatibility)</li>";
} elseif ($legacyInserted === 'skipped') {
    echo "     <li><code>listings</code> — already had data, skipped</li>";
} else {
    echo "     <li><code>listings</code> — <span style='color:#dc2626;'>insert failed (table may not exist)</span></li>";
}

echo "  </ul></div>"

   . "<div style='display:flex;gap:0.75rem;justify-content:center;flex-wrap:wrap;'>"
   . "<a href='" . htmlspecialchars((defined('BASE') ? BASE : '/')) . "/' class='btn'>View Homepage</a>"
   . "<a href='opportunities.php' class='btn btn-secondary'>Browse Opportunities</a>"
   . "</div>"

   . "<div class='danger'>"
   . "<strong>⚠ DELETE THIS FILE AFTER USE</strong><br>"
   . "For security, remove <code>seed_opportunities.php</code> from your server once done."
   . "</div>"

   . "</div></body></html>";
