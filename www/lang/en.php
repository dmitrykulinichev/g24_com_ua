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
];
