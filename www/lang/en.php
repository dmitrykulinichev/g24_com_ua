<?php

// English. Same keys as uk.php; missing ones fall back to Ukrainian.

return [
    'meta' => [
        'site_title' => 'Garage24 - CRM for vehicle fleets',
        'site_description' => 'Automate driver payouts, fuel control and day-to-day work with drivers. Connect now and take your fleet to the next level.',
    ],

    'nav' => [
        'features' => 'Features',
        'target' => 'Who it is for',
        'pricing' => 'Pricing',
        'blog' => 'Blog',
        'docs' => 'Documentation',
        'contacts' => 'Contacts',
        'login' => 'Open garage',
        'login_mobile' => 'Log in to the cabinet',
        'menu' => 'Menu',
    ],

    'footer' => [
        'tagline' => 'Modern tools for running a vehicle fleet. Automate the routine and grow your profit.',
        'product' => 'Product',
        'info' => 'Information',
        'contact' => 'Contact us',
        'privacy' => 'Privacy policy',
        'terms' => 'Terms of use',
        'offer' => 'Public offer',
        'rights' => 'All rights reserved.',
        'target' => 'Who it is for',
    ],

    'cookies' => [
        'text' => 'We use cookies to make the site work better. By continuing to browse, you agree to our',
        'privacy_link' => 'privacy policy',
        'accept' => 'Got it',
    ],

    'contacts' => [
        'form' => [
            'success' => ['title' => 'Message sent!', 'text' => 'We will get back to you shortly.'],
            'recaptcha' => ['text' => 'This site is protected by reCAPTCHA and the Google', 'privacy' => 'Privacy Policy', 'and' => 'and', 'terms' => 'Terms of Service'],
            'errors' => ['required' => 'This field is required', 'general' => 'Something went wrong. Please try again later.', 'network' => 'Network error. Please try again later.'],
            'fallback' => [
                'name' => ['label' => 'Your name', 'placeholder' => 'John Smith'],
                'email' => ['label' => 'Email'],
                'phone' => ['label' => 'Phone'],
                'message' => ['label' => 'Your question', 'placeholder' => 'For example: do you integrate with Bolt?'],
            ],
            'submit' => 'Send request',
            'sending' => 'Sending...',
        ],
    ],

    'order_form' => [
        'success' => ['check_email' => 'Check your inbox (including Spam) to activate your account.', 'login' => 'Log in to the cabinet', 'forgot' => 'Forgot your password?', 'restore' => 'Restore access'],
        'resend' => ['button' => 'Send the email again', 'sending' => 'Sending...', 'wait_prefix' => 'Wait ', 'wait_suffix' => 's'],
        'help' => ['text' => 'Trouble signing in or registering?', 'link' => 'write to us'],
        'consent' => 'I agree to the :privacy and the :offer',
        'consent_privacy' => 'Privacy policy',
        'consent_offer' => 'Public offer',
        'js' => [
            'fields' => ['name' => 'Your name', 'name_placeholder' => 'John Smith', 'email_login' => 'Email (login)', 'park_name' => 'Fleet / company name', 'park_name_placeholder' => 'Fleet LLC', 'phone' => 'Phone', 'message' => 'Your message', 'message_placeholder' => 'Number of vehicles, special requests...'],
            'status' => ['already_active' => 'Your account is already activated.', 'not_activated' => 'You are already registered but not activated yet.', 'registered' => 'Your fleet has been registered.', 'lead_accepted' => 'Thank you! We have received your request.', 'account_active' => 'The account is already activated.', 'resent' => 'The email has been sent again!'],
            'title' => ['enterprise' => 'Custom terms', 'register' => 'Fleet registration'],
            'button' => ['processing' => 'Processing...', 'consultation' => 'Request a consultation', 'create' => 'Create account'],
            'errors' => ['consent' => 'Please confirm that you agree to the terms.', 'required' => 'This field is required', 'server' => 'Server error.', 'network' => 'Network error. Please try again later.', 'send' => 'Sending failed.', 'network_short' => 'Network error.'],
        ],
        'plans' => [
            'monthly' => ['title' => 'Monthly payment', 'text' => 'Pay as you go at the end of the month'],
            'yearly' => ['title' => 'Annual subscription', 'text' => '100 UAH per car discount'],
            'note' => 'You can change your plan at any time in your account.',
        ],
    ],

    'blog' => [
        'index' => [
            'title' => 'Product blog',
            'subtitle' => 'News, updates and practical tips on using Garage24.',
            'read_more' => 'Read more',
            'pagination' => 'Blog pages',
            'newer' => 'Newer',
            'older' => 'Older',
            'page_of' => 'Page :page of :total',
            'empty' => 'No news yet.',
        ],
        'post' => [
            'all' => 'All news',
            'published' => 'Published:',
        ],
        'search' => [
            'placeholder' => 'Search news...',
        ],
    ],

    'docs' => [
        'meta' => [
            'title' => 'Documentation',
            'description' => 'Guides, tips and answers to common questions about Garage24, the CRM for running a vehicle fleet.',
        ],
        'index' => [
            'title' => 'Garage24 knowledge base',
            'subtitle' => 'Guides, tips and answers to common questions.',
            'go_to_section' => 'Go to the ":title" section.',
            'read' => 'Read',
            'help' => [
                'title' => 'Did not find an answer?',
                'text' => 'Our support team is ready to help.',
                'cta' => 'Contact support',
            ],
        ],
        'page' => [
            'contents' => 'Section contents',
            'nav' => 'Documentation',
            'sections' => 'Sections: :title',
            'breadcrumb' => 'Documentation',
        ],
        'placeholder' => [
            'alt' => 'Screenshot',
            'title' => 'An image is coming soon',
            'text' => 'We are working on the documentation',
        ],
        'search' => [
            'placeholder' => 'Search...',
        ],
    ],

    'search' => [
        'nothing_found' => 'Nothing found',
    ],

    'language' => [
        'label' => 'Language',
    ],

    'errors' => [
        '404' => [
            'title' => '404 - Page not found',
            'description' => 'The page you are looking for does not exist.',
            'heading' => 'Page not found',
            'text' => 'Looks like you got lost in the garage. The page you are looking for has been moved or deleted.',
            'home' => 'Back to home',
            'contact' => 'Contact us',
        ],
    ],

    // Home page (views/landing.blade.php)
    'landing' => [
        'meta' => [
            'title' => 'CRM for vehicle fleets',
            'description' => 'Automate driver payouts, fuel control and day-to-day work with drivers. Connect now and take your fleet to the next level.',
        ],
        'hero' => [
            'title_1' => 'The operating system for',
            'title_2' => 'a modern taxi fleet',
            'subtitle' => 'Replace Excel, notebooks and chaotic chats with a single digital ecosystem. Automate schedules, finances and repairs in one window',
        ],
        'benefits' => [
            'pay_as_you_go' => [
                'title' => 'Pay as you go',
                'text' => 'Use it first, pay later. No prepayments',
            ],
            'all_included' => [
                'title' => 'All included',
                'text' => 'One plan. Every feature available from day one. No "Pro" editions',
            ],
            'active_only' => [
                'title' => 'Pay for active cars only',
                'text' => 'Car in for repairs? You don\'t pay for it. We only count cars that are working',
            ],
        ],
        'cta' => [
            'start' => 'Get started',
            'target' => 'Who it\'s for',
            'setup_note' => 'Setup takes less than 15 minutes. Free start.',
            'title' => 'Order costs less than chaos',
            'text' => 'Start today. Your first invoice arrives only a month later, and only if the system has been useful to you.',
            'note' => 'No card required. Pay as you go.',
        ],
        'pains' => [
            'title' => 'Sound familiar?',
            'subtitle' => 'A typical story when scaling up: the business grows, and control slips away.',
            'finance' => [
                'title' => 'Finances in a "black box"',
                'text' => 'You see money moving, but you don\'t know the real profit of each car. Money gets lost between spreadsheets and cards.',
            ],
            'schedule' => [
                'title' => 'Chaotic schedules',
                'text' => 'The dispatcher keeps shifts in their head. Cars sit idle because someone "forgot" to find a driver. Fines go to the wrong people.',
            ],
            'repairs' => [
                'title' => 'Repairs as "firefighting"',
                'text' => 'Oil changes come up only when the engine starts knocking. Repair history lives in Viber chats that nobody can check.',
            ],
        ],
        'app' => [
            'badge' => 'Android and iOS',
            'title' => 'An app for drivers and staff',
            'subtitle' => 'Drivers log everything from their phone the moment it happens. Staff see it right away and work with drivers from their phone too, with no calls and no paperwork.',
            'items' => [
                'Car pick-up and return with the odometer reading',
                'Cash handovers, refuelling and expenses in two taps',
                'Fault reports with a status and the fleet\'s reply',
                'Two-week schedule, fleet contacts and instructions, SOS',
                'Works offline: entries are sent automatically later',
            ],
            'staff' => 'Staff get their own mode: drivers, payouts, deposits, expenses, maintenance and documents. Ukrainian, English and Russian, dark theme.',
        ],
        'modules' => [
            'title' => 'Order instead of chaos',
            'subtitle' => 'Everything for the fleet\'s daily work in one system.',
            'items' => [
                'schedule' => [
                    'title' => 'Schedule and assignments',
                    'text' => 'Shifts planned ahead without overlaps: the system won\'t let you put one driver on two cars.',
                    'items' => [
                        'Drag and drop in the calendar',
                        'Pick-up and return with mileage',
                        'Who drove the car and when',
                    ],
                ],
                'payouts' => [
                    'title' => 'Earnings and payouts',
                    'text' => 'Driver settlements based on Uklon trips and cash, with bonuses, fines and expenses.',
                    'items' => [
                        'Revenue split schemes',
                        'Payouts and deposits',
                        'Driver expenses with approval',
                    ],
                ],
                'economics' => [
                    'title' => 'Fleet economics',
                    'text' => 'Income, expenses and profit by month and for every car.',
                    'items' => [
                        'Profit of each car',
                        'Expense categories',
                        'Uklon branding bonuses',
                    ],
                ],
                'fleet' => [
                    'title' => 'Fleet and documents',
                    'text' => 'A card for every car with photos, documents and history.',
                    'items' => [
                        'Insurance reminders',
                        'Car condition inspections',
                        'Retiring a car without losing its history',
                    ],
                ],
                'drivers' => [
                    'title' => 'Drivers and hiring',
                    'text' => 'Driver documents with expiry dates, self-registration, invitations from Uklon.',
                    'items' => [
                        'Driver sign-up from a phone',
                        'Documents with reminders',
                        'Inviting a driver from Uklon',
                    ],
                ],
                'service' => [
                    'title' => 'Maintenance and repairs',
                    'text' => 'Service schedule, repair shop costs, driver fault reports.',
                    'items' => [
                        'Fault reports with a status',
                        'Scheduled maintenance',
                        'Repair shop cost control',
                    ],
                ],
                'mileage' => [
                    'title' => 'Mileage and fuel',
                    'text' => 'Mileage and refuelling from the driver\'s phone, every day.',
                    'items' => [
                        'Daily mileage',
                        'Refuelling with litres and amount',
                        'Who hasn\'t reported mileage today',
                    ],
                ],
                'security' => [
                    'title' => 'Roles and activity log',
                    'text' => 'Managers, mechanics and accountants each see their own part. A full history of actions.',
                    'items' => [
                        'Access roles',
                        'Activity log',
                        'Daily backups',
                    ],
                ],
            ],
        ],
        'uklon' => [
            'badge' => 'Uklon integration',
            'title' => 'Sync with the Uklon Fleet API',
            'text' => 'Forget about copying data by hand. Garage24 connects to your partner account and pulls in all the data automatically.',
            'trips' => [
                'title' => 'Trips and cash import',
                'text' => 'All orders, bonuses and tips go straight into the financial report.',
            ],
            'sync' => [
                'title' => 'Database sync',
                'text' => 'Load your drivers and cars from Uklon in one click (Link Fleet).',
            ],
            'statuses' => [
                'title' => 'Status control',
                'text' => 'See in real time who is online and who is resting.',
            ],
        ],
        'ecosystem' => [
            'title' => 'The Garage24 ecosystem',
            'subtitle' => 'Everyone works where it suits them.',
            'cabinet' => [
                'title' => 'Fleet dashboard',
                'text' => 'For the owner and managers: full fleet management. Works in the browser on a computer and a phone.',
                'items' => [
                    'Accounting and reports',
                    'Mobile version',
                    'Roles for every employee',
                ],
            ],
            'app' => [
                'title' => 'App',
                'text' => 'For drivers and staff. Drivers run their shift; staff handle drivers, payouts and maintenance from a phone.',
                'items' => [
                    'Android and iOS',
                    'Works offline',
                    'Dark theme',
                ],
            ],
            'bot' => [
                'title' => 'Telegram bot',
                'text' => 'For fleet groups: a driver sends a short message, the bot records it and replies with 👌.',
                'items' => [
                    'Pick-up and return, mileage',
                    'Cash and refuelling',
                    'Fault reports',
                ],
                'chat' => [
                    [
                        'author' => 'Alex',
                        'text' => '5683 returned 76081',
                        'reply' => '',
                    ],
                    [
                        'author' => 'Oleksii',
                        'text' => '5683 revs are unstable, needs the LPG mechanic',
                        'reply' => '',
                    ],
                    [
                        'author' => 'Ihor',
                        'text' => 'Took.8392.191569',
                        'reply' => 'Thank you! I understood it as: car 8392 picked up, mileage 191,569 km. Next time, please write it like this: 8392 picked up 191569',
                    ],
                ],
            ],
        ],
        'faq' => [
            'title' => 'Frequently asked questions',
            'items' => [
                [
                    'q' => 'Can I move my data over from my old Excel spreadsheet?',
                    'a' => 'Yes! Our import module lets you load drivers and cars in a few minutes. The system will suggest how to match the columns. You can also sync drivers and cars with the Uklon database.',
                ],
                [
                    'q' => 'Is there a mobile app?',
                    'a' => 'Yes, for Android and iOS, for drivers and staff. Drivers pick up and return cars, log cash, mileage, refuelling, expenses and fault reports, and see their schedule; it works offline too. Staff, in their own mode, manage drivers, payouts, deposits, expenses and maintenance from a phone.',
                ],
                [
                    'q' => 'Is it safe to keep data in the cloud?',
                    'a' => 'We use reliable data isolation and encrypt sensitive information. Daily backups make sure you never lose your database. On request, we can consider connecting our system to your own database. However, for a small fleet our storage is the better choice.',
                ],
                [
                    'q' => 'Do I need a programmer to set it up?',
                    'a' => 'No. The system is built for ordinary users. Everything is configured through a clear interface.',
                ],
            ],
        ],
        'schema' => [
            'offer' => 'Free start, pay as you go',
        ],
    ],

    'screens' => [
        'cabinet-dashboard' => 'Garage24 web dashboard: fleet overview',
        'uklon-reports' => 'Uklon reports in Garage24: revenue and driver ranking',
        'app-home' => 'Garage24 app: driver home screen',
        'app-driver-trio' => 'The app: driver schedule, the “+” button and issue reports',
    ],
];
