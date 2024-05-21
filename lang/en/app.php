<?php

return [
    'navigation' => [
        'home' => 'Home',
        'blog' => 'Blog',
        'my_pets' => 'My Pets',
        'verified' => 'Verified',
        'close_button' => 'Close Menu',
        'open_button' => 'Open Menu',
        'change_language' => 'Change Language',
        'close' => 'Close',
        'save_changes' => 'Save Changes',
        'search' => 'Search',
        'current' => '(current)',
        'livestock' => [
            'slug' => 'livestock',
            'name' => 'Livestock',
            'description' => 'Often kept on farms. Most common species: Cows, Pigs, Sheep, Goats, Ducks, Chickens.',
            'food_details' => [
                'cows' => 'Cows primarily eat grass and hay. Some may also be fed grains and silage.',
                'pigs' => 'Pigs have omnivorous diets, including grains, vegetables, fruits, and occasionally meat scraps.',
                'sheep' => 'Sheep graze on grass, hay, and sometimes grains like barley and oats.',
                'goats' => 'Goats are browsers and eat a variety of plants, including grass, leaves, and shrubs.',
                'ducks' => 'Ducks eat a diet rich in grains, insects, aquatic plants, and small fish.',
                'chickens' => 'Chickens are omnivores and eat grains, seeds, insects, and even small rodents.'
            ],
        ],
        // 'species' => [
        //     'slug' => 'species',
        // ],
    ],
    'footer' => 'Source of information on what you can or cannot feed to your pets, provided by the community.',
    'section' => [
        'species' => [
            'name' => 'Species', 
        ],
        'profile' => [
            'name' => 'My Profile',
            'old_image' => 'OLD IMAGE',
            'new_image' => 'NEW IMAGE',
            'choose_image' => 'Choose Image',
            'upload' => 'Upload',
            'connections' => 'Connections',
            'not_connected' => 'Not connected',
            'username' => 'Username',
            'full_name' => 'Full Name',
            'email' => 'E-mail',
            'set_display_name' => 'Set as display name',
            'password_change' => 'Change Password',
            'current_password' => 'Current Password',
            'new_password' => 'New Password',
            'confirm_password' => 'Confirm Password',
            'change_password' => 'Change Password',

            'not_verified' => 'Not verified',
            'how_why' => 'How/Why?',
            'verified_badge' => 'Verified Badge',
            'verified_badge_step_1' => 'Scan the front side of the document of your choice - ID card or passport.',
            'verified_badge_step_2' => 'Then you will be asked to scan a picture of yourself to complete the verification process.',
            'get_verified' => 'Get Verified',

            'roles' => 'Roles',
            'user' => 'User',
            'more_details' => 'More Details',
            'requirements_title' => 'Requirements',
            'apply_button' => 'Apply all of my pets',
            'cannot_apply_button' => 'You cannot apply to this role',
            'requirements' => [
                'pet_owner' => [
                    'title' => 'Pet Owner',
                    'requirement_list' => [
                        'One of your saved pets, along with an image associated to it.',
                    ],
                    'link' => 'My Pets section',
                ],
                'content_creator' => [
                    'title' => 'Content Creator',
                    'requirement_list' => [
                        'Get verified by any admin or auditor',
                        'Get the <a href="">verified</a> badge',
                    ],
                    'description' => 'We will send you an email to walk you through the steps to being a content creator.',
                ],
                'auditor' => [
                    'title' => 'Auditor',
                    'requirement_list' => [
                        'You need to already be a verified content creator.',
                    ],
                    'description' => 'Being a content auditor is no joke, we have to be sure that our website\'s content isn\'t only accurate and up-to-date but also aligns with the brand\'s tone and messaging, meets SEO standards, and complies with any relevant legal and ethical guidelines. An auditors duties include approving the content creators created content and evaluating it before it is public.',
                ],
                'expert' => [
                    'title' => 'Expert',
                    'requirement_list' => [
                        'Any valid sertificate of completion from a government entity.',
                        'The sertificate has to be anything related to pets.',
                    ],
                    'description' => 'Anyone who has had the experience in any of these fields can be a valuable asset to our community. Here are some examples:',
                    'example_list' => [
                        'Veterinary Medicine',
                        'Pet Grooming',
                        'Pet Training',
                        'Pet Sitting and Dog Walking',
                        'Pet Retail',
                        'Pet Food Industry',
                        'Animal Shelter and Rescue',
                        'Pet Photography',
                        'Animal Behaviorist',
                        'Pet Insurance',
                    ],
                ],
                'admin' => [
                    'title' => 'Admin',
                    'description' => 'Being an admin is the highest responsibility of any community. The admins oversee all activity on the website.',
                ],
            ],
        ],
    ],
];