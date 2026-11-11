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
        'driver' => 'For drivers',
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
        'meta' => ['title' => 'Contacts', 'description' => 'Contact us for a consultation or technical support. We are always in touch.'],
        'hero' => ['title' => 'Contact us', 'subtitle' => 'Write to us using the form below — we will get back to you shortly.'],
        'clients' => [
            'title' => 'Already a Garage24 client?',
            'text' => 'This form is for general questions. If you already use Garage24, include the name of your fleet in the message.',
            'text_2' => 'That way we see your fleet right away and can help faster.',
        ],
        'form' => [
            'title' => 'Not with us yet?',
            'text' => 'Fill in the form if you have questions about getting started, pricing or what the system can do.',
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
            // Посилання на посібник водія (/driver) над списком розділів
            'driver_banner' => [
                'question' => 'Are you a driver?',
                'link' => 'Driver guide',
            ],
            'help' => [
                'title' => 'Did not find an answer?',
                'text' => 'Our support team is ready to help.',
                'cta' => 'Contact support',
            ],
        ],
        'badge' => [
            'new' => 'New',
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

    // Посібник водія — окремий розділ (/driver, content/driver; DriverGuideController, шаблони документації)
    'driver' => [
        'meta' => [
            'title' => 'Driver guide',
            'description' => 'How drivers use the Garage24 app: signing in with a phone number, taking and returning a vehicle, shift records.',
        ],
        'index' => [
            'title' => 'Driver guide',
            'subtitle' => 'How to work in the Garage24 app: sign-in, shift, records.',
        ],
        'page' => [
            'nav' => 'Driver guide',
            'breadcrumb' => 'Driver guide',
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

    // Features (views/features.blade.php)
    'features' => [
        'meta' => [
            'title' => 'System features',
            'description' => 'A detailed look at what Garage24 does: from vehicle records to financial analytics and integrations.',
        ],
        'hero' => [
            'title' => 'System features',
            'subtitle' => 'Garage24 is a complete solution that covers every need of a modern fleet.',
        ],
        'sections' => [
            'dashboard' => [
                'title' => 'Dashboard',
                'slogan' => 'The pulse of your business on one screen.',
                'items' => [
                    ['title' => 'Status monitoring', 'desc' => 'How many cars are working, available or in repair. Fleet utilisation rate.'],
                    ['title' => 'Notification centre', 'desc' => 'Automatic reminders about maintenance and expiring insurance.'],
                    ['title' => 'Live incidents', 'desc' => 'Open problem reports from drivers that need an immediate response.'],
                ],
            ],
            'fleet' => [
                'title' => 'Fleet management',
                'slogan' => 'Full control over every car.',
                'items' => [
                    ['title' => 'Digital file', 'desc' => 'Make, model, VIN, plate number. History of driver assignments.'],
                    ['title' => 'Bulk import', 'desc' => 'Load your vehicle database from Excel/CSV files in 2 minutes.'],
                    ['title' => 'Plans and performance', 'desc' => 'Set financial targets for each vehicle class.'],
                    ['title' => 'QR technology', 'desc' => 'Quick access to vehicle information by scanning a code.'],
                ],
            ],
            'drivers' => [
                'title' => 'Drivers and HR',
                'slogan' => 'From hiring to the first shift in minutes.',
                'items' => [
                    ['title' => 'Digital profile', 'desc' => 'Contacts, documents, photos, work history.'],
                    ['title' => 'Quick sign-up', 'desc' => 'A public link where candidates register themselves.'],
                    ['title' => 'Documents with reminders', 'desc' => 'Driver documents with expiry dates; the system reminds you when something runs out.'],
                ],
            ],
            'schedule' => [
                'title' => 'Smart schedule',
                'slogan' => 'Shift planning without conflicts or overlaps.',
                'items' => [
                    ['title' => 'Visual matrix', 'desc' => 'A "Vehicle — Day — Shift" grid. Easy drag-and-drop management.'],
                    ['title' => 'Conflict control', 'desc' => 'The system won’t let you assign one driver to two cars at the same time.'],
                    ['title' => 'Slot types', 'desc' => 'Work shift, repair, maintenance (automatically blocks the car).'],
                ],
            ],
            'finance' => [
                'title' => 'Finance and transactions',
                'slogan' => 'Every hryvnia under control.',
                'items' => [
                    ['title' => 'Driver settlements', 'desc' => 'Based on Uklon trips and cash, with bonuses, fines and expenses; revenue split schemes.'],
                    ['title' => 'Vehicle profitability', 'desc' => 'Net profit of each car (income minus expenses).'],
                    ['title' => 'Bonuses and fines', 'desc' => 'A flexible staff motivation system.'],
                    ['title' => 'Driver balances', 'desc' => 'Track what each driver owes the fleet.'],
                ],
            ],
            'reports' => [
                'title' => 'Reports and analytics',
                'slogan' => 'Data for making the right decisions.',
                'items' => [
                    ['title' => 'Live statistics', 'desc' => 'Real-time tracking of plan progress with forecasts.'],
                    ['title' => 'Targets vs actuals', 'desc' => 'Historical analysis of fleet performance (actual vs plan).'],
                    ['title' => 'Uklon reports', 'desc' => 'Revenue, trips, average fare and mileage by driver and vehicle for any period.'],
                ],
            ],
            'integrations' => [
                'title' => 'Integrations',
                'slogan' => 'One ecosystem for your business.',
                'items' => [
                    ['title' => 'Uklon Fleet API', 'desc' => 'Full sync of orders, cash and the driver database.'],
                    ['title' => 'Telegram bot', 'desc' => 'For fleet groups: mileage, car pick-up and return, cash, refuelling and issues in a short message.'],
                ],
            ],
            'maintenance' => [
                'title' => 'Maintenance',
                'slogan' => 'Transparent repair costs and service history.',
                'items' => [
                    ['title' => 'Work log', 'desc' => 'Every visit to the repair shop is recorded, split into scheduled maintenance and repairs.'],
                    ['title' => 'Cost tracking', 'desc' => 'Cost of parts and labour. Photos of receipts and work orders.'],
                    ['title' => 'Repair requests', 'desc' => 'Drivers report faults from their smartphone.'],
                ],
            ],
            'settings' => [
                'title' => 'Settings and administration',
                'slogan' => 'Flexibility for your business processes.',
                'items' => [
                    ['title' => 'Roles and access', 'desc' => 'Permissions for managers, mechanics and accountants.'],
                    ['title' => 'Activity log', 'desc' => 'A full history of user actions for security.'],
                ],
            ],
            'mobility' => [
                'title' => 'Mobility',
                'slogan' => 'Run your business from anywhere.',
                'items' => [
                    ['title' => 'Responsive interface', 'desc' => 'Managers can run the fleet from a smartphone.'],
                    ['title' => 'App for drivers and staff', 'desc' => 'Android and iOS: drivers run their shift; staff handle drivers, payouts and maintenance from a phone.'],
                ],
            ],
        ],
        'cta' => [
            'title' => 'Ready to streamline your business?',
            'text' => 'Get full access to every feature. Pay only for active cars at the end of the month.',
            'start' => 'Get started',
            'docs' => 'Documentation →',
        ],
    ],

    // Who it's for (views/target.blade.php)
    'target' => [
        'meta' => [
            'title' => 'Who Garage24 is for',
            'description' => 'A solution for fleet owners, partners and investors. See how we solve your problems.',
        ],
        'hero' => [
            'title' => 'Who is Garage24 for?',
            'subtitle' => 'We built the system around the specifics of different fleet management models.',
        ],
        'segments' => [
            'partners' => [
                'title' => 'Co-owners',
                'text' => 'You run the business with one or two partners. One handles finances, the other the technical side.',
                'pain' => 'Constant arguments about "where’s the money?". One sees profit on the card, the other sees repair bills. There is no single picture.',
                'solution' => 'One database. Both partners see every transaction, every repair and the real balance. Full transparency.',
            ],
            'operator' => [
                'title' => 'The owner who "lives in the garage"',
                'text' => 'You find drivers, oversee repairs and pay salaries yourself. Your phone rings every 5 minutes.',
                'pain' => 'You are the bottleneck of the business. If you get sick or go on holiday, everything stops.',
                'solution' => 'A mobile office. Drivers log everything in the app instead of calling you; staff work from their phones. You manage processes instead of putting out fires.',
            ],
            'investor' => [
                'title' => 'Owner-investor',
                'text' => 'You bought the cars and hired a manager (dispatcher). You want passive income.',
                'pain' => 'Lack of trust. Are all trips accounted for? Are parts overpriced? Are the cars used "on the side"?',
                'solution' => 'Total control. The Uklon integration shows real trips. The activity log shows who changed what in the system.',
            ],
            'growing' => [
                'title' => 'A growing fleet (10+ cars)',
                'text' => 'You used to manage with Excel and a notebook. But the fleet grew, and the system broke.',
                'pain' => 'Chaos. Lost documents, overdue maintenance, mixed-up schedules. Excel no longer works.',
                'solution' => 'Structure. Garage24 automates routine work (reminders, schedules, reports), so you can keep growing without hiring more managers.',
            ],
        ],
        'label_pain' => 'Pain:',
        'label_solution' => 'Solution:',
        'reviews' => [
            'title' => 'What our clients say',
            'subtitle' => 'Real stories from people who have already brought order.',
            'items' => [
                [
                    'text' => '"My partner and I kept arguing about money. He said we were in the black, and all I saw was repair bills. Garage24 showed the real picture. Now we see the profitability of every car down to the last kopiyka."',
                    'name' => 'Dmytro',
                    'role' => 'Co-owner (18 cars)',
                ],
                [
                    'text' => '"My phone used to ring every 5 minutes: \'Where’s the car?\', \'Who’s on shift?\'. Now I just open the site on my phone and see everything myself. The calls stopped, and I can finally focus on growth."',
                    'name' => 'Oleksandr',
                    'role' => 'Fleet owner (25 cars)',
                ],
                [
                    'text' => '"Copying trips from the Uklon account into our spreadsheet took the most time — hours of manual work. Now everything comes in automatically. I don’t even open the partner account anymore."',
                    'name' => 'Andrii',
                    'role' => 'Owner (12 cars)',
                ],
            ],
        ],
        'cta' => [
            'start' => 'Get started',
        ],
    ],

    // Pricing (views/pricing.blade.php)
    'pricing' => [
        'meta' => [
            'title' => 'Pricing',
            'description' => 'An honest price with no hidden fees. Pay for actual use. Try it for free.',
        ],
        'hero' => [
            'title_1' => 'Use it first —',
            'badge' => 'A new approach to fleet accounting',
            'title_2' => 'pay later',
            'subtitle' => 'We’ve dropped prepayments and complicated plans. Sign up, bring order to your fleet, and we’ll invoice you only at the end of the month.',
        ],
        'formula' => [
            'badge_yearly' => 'Best price',
            'badge_monthly' => 'Pay as you go',
            'title' => 'The simple maths of your success',
            'subtitle' => 'You pay only for what actually makes money.',
            'monthly' => 'Monthly payment',
            'yearly' => 'Annual subscription',
            'yearly_discount' => '-50% per car',
            'base' => 'Base',
            'base_text' => 'Monthly subscription fee',
            'base_note_monthly' => 'for system access and hosting',
            'base_note_yearly' => 'paid for 12 months',
            'scale' => 'Scale',
            'per_car' => 'Per active car',
            'per_month' => 'per month',
        ],
        'cta' => [
            'monthly' => 'Get started now',
            'no_card' => 'No card required',
            'invoice_note' => 'Your invoice arrives in 30 days',
            'billing_link' => 'More on how we calculate payment →',
            'yearly' => 'Get the annual subscription',
        ],
        'active_car' => [
            'condition' => '5 shifts',
            'text' => 'We count a car as active only if it worked <strong>at least :condition</strong> during the billing month. If the car was in repair, waiting for a driver or sold — <strong>you don’t pay for it.</strong>',
            'title' => 'What is an "active car"?',
        ],
        'faq' => [
            'items' => [
                [
                    'q' => 'How is the invoice issued?',
                    'a' => 'The invoice is generated automatically the day after your billing period ends (a month from your sign-up date). You receive it by email and in your account. You have 5 days to pay.',
                ],
                [
                    'a' => 'We don’t use the "end of the calendar month". We count car activity during <strong>your personal billing period</strong> (a month from your sign-up date). If a car worked fewer than 5 shifts in that time, you are not charged for it.',
                    'q' => 'What if I added a car at the end of the month?',
                ],
                [
                    'q' => 'Can I pay as a sole proprietor or a company?',
                    'a' => 'Yes, we work officially and provide all the documents your accounting needs.',
                ],
                [
                    'q' => 'Can I set up automatic payments?',
                    'a' => 'Yes. You can link a card for automatic payment. We will notify you of the amount 24 hours before the charge and send a receipt after payment.',
                ],
                [
                    'q' => 'Who is Garage24 NOT for?',
                    'a' => 'Garage24 won’t suit you if you have no cars of your own (you work only as a dispatch service) or if you need a complex ERP system for a large logistics company. We focus on taxi fleets.',
                ],
            ],
            'title' => 'Payment questions',
        ],
        'features' => [
            'Full integration with the Uklon Fleet API',
            'Telegram bot for drivers and managers',
            'Smart shift schedule (drag & drop)',
            'Driver payout calculation (salary)',
            'Repair records and spare parts stock',
            'Mobile app (PWA) for the owner',
            '24/7 technical support',
            'Daily database backups',
        ],
        'currency' => 'UAH',
        'trust' => '"We run a fleet ourselves. Garage24 didn’t start as a startup — it was our answer to the real chaos we faced every day."',
        'why' => [
            'title' => 'Why is this better than regular plans?',
            'items' => [
                [
                    'title' => 'Save on downtime',
                    'text' => 'You have 20 cars, but 5 are in repair? Other systems charge you for an "up to 20" package. We charge only for the 15 that work.',
                ],
                [
                    'title' => 'Easy start',
                    'text' => 'No need to pay 5,000 UAH upfront just to try it. Start small and pay from your profit.',
                ],
                [
                    'title' => 'No limits',
                    'text' => 'We don’t restrict features. You get full access to every module, even with just 3 cars.',
                ],
            ],
        ],
        'included' => [
            'title' => 'Everything included',
            'text' => 'We don’t split clients into tiers. You get the full feature set right away, including future updates.',
            'bonus' => [
                'title' => 'Loyalty bonus',
                'text' => 'Pay the subscription fee (base) a year in advance and get a discount.',
                'per_car_month' => '/ car per month',
            ],
            'extra' => [
                'Free system updates',
                'Personal onboarding',
            ],
        ],
        'partnership' => [
            'title' => 'We are open to partnership',
            'text' => 'We don’t just sell software — we build a community. If you need a specific feature, integration or special terms, we are ready to discuss it.',
            'tags' => [
                'Custom development',
                'Dedicated server',
                'White label',
            ],
            'cta' => 'Discuss ideas',
        ],
    ],

    'screens' => [
        'cabinet-dashboard' => 'Garage24 web dashboard: fleet overview',
        'uklon-reports' => 'Uklon reports in Garage24: revenue and driver ranking',
        'app-home' => 'Garage24 app: driver home screen',
        'app-driver-trio' => 'The app: driver schedule, the “+” button and issue reports',
        'cabinet-vehicles' => 'Web dashboard: fleet vehicle list',
        'cabinet-drivers' => 'Web dashboard: driver list',
        'cabinet-schedule' => 'Web dashboard: driver schedule',
        'cabinet-payouts' => 'Web dashboard: driver payouts',
        'cabinet-uklon-orders' => 'Web dashboard: Uklon orders',
        'cabinet-maintenance' => 'Web dashboard: vehicle maintenance',
        'cabinet-activity-logs' => 'Web dashboard: system activity log',
        'app-signin' => 'Garage24 app: sign-in screen with your phone number',
        'app-signin-code' => 'Signing in: the code field',
        'app-signin-code-telegram' => 'Signing in: the code was sent to the fleet\'s Telegram bot',
        'app-register-new-driver' => 'New driver sign-up: the app says the number isn\'t registered yet',
        'app-register-profile-filled' => 'Driver sign-up: personal details filled in',
        'app-register-password' => 'Driver sign-up: password for signing in',
        'app-first-permissions' => 'The app after sign-up: permissions',
        'app-new-driver-home' => 'New driver home screen: no vehicle yet',
        'app-telegram-code' => 'Sign-in code message from the fleet\'s Telegram bot',
        'app-vehicle-choose' => 'Driver app: available fleet vehicles',
        'app-vehicle-accept' => 'Accepting a vehicle: odometer reading',
        'app-vehicle-mine' => 'Driver app: my vehicle after accepting it',
        'app-home-with-car' => 'Driver home screen with a vehicle',
        'app-vehicle-handover' => 'Returning a vehicle: odometer reading',
        'app-vehicle-handover-done' => 'Vehicle returned: can be undone',
        'app-quickadd-menu' => 'The app: the «+» menu with shift records',
        'app-add-manual-trip' => 'The app: recording a cash trip',
        'app-add-mileage' => 'The app: updating mileage',
        'app-add-fuel' => 'The app: new refueling',
        'app-add-expense' => 'The app: new expense',
        'app-add-issue' => 'The app: new issue',
        'app-record-undo' => 'Record saved: can be undone',
        'app-records-menu' => 'App menu: my records',
        'app-record-actions' => 'A record: edit or delete',
        'app-schedule' => "The app: the driver's shift schedule",
        'app-vehicle-general' => "The app: your vehicle",
        'app-vehicle-documents' => "The app: the vehicle's documents",
        'app-info' => "The app: the fleet's info and contacts",
        'app-info-accident' => "Info: what to do after an accident",
    ],

    'screen_captions' => [
        'app-signin' => 'Phone number',
        'app-signin-code' => 'SMS code',
        'app-signin-code-telegram' => 'Code in Telegram',
        'app-home' => 'Home screen',
        'app-register-new-driver' => 'Fleet code',
        'app-register-profile-filled' => 'Your details',
        'app-register-password' => 'Password',
        'app-first-permissions' => 'Permissions',
        'app-new-driver-home' => 'Home screen',
        'app-telegram-code' => 'Telegram',
        'app-vehicle-choose' => 'Available',
        'app-vehicle-accept' => 'Mileage',
        'app-vehicle-mine' => 'My Vehicle',
        'app-home-with-car' => 'Return Vehicle',
        'app-vehicle-handover' => 'Mileage',
        'app-vehicle-handover-done' => 'Returned',
        'app-quickadd-menu' => '«+» menu',
        'app-add-manual-trip' => 'Trip',
        'app-add-mileage' => 'Mileage',
        'app-add-fuel' => 'Fuel',
        'app-add-expense' => 'Expense',
        'app-add-issue' => 'Issue',
        'app-record-undo' => 'Undo',
        'app-records-menu' => 'My records',
        'app-record-actions' => 'Edit or delete',
        'app-schedule' => 'Schedule',
        'app-vehicle-general' => 'General',
        'app-vehicle-documents' => 'Documents',
        'app-info' => 'Info',
        'app-info-accident' => 'Accident',
    ],
];
