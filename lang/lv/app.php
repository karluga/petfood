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
            'slug' => 'majlopi',
            'name' => 'Mājlopi',
            'description' => 'Bieži tiek turēti saimniecībās. Visbiežāk sastopamās sugas: govis, cūkas, aitas, kazas, pīles, vistas.',
            'food_details' => [
                'govis' => 'Govis galvenokārt ēd zāli un sienu. Dažas var tikt barotas arī ar graudiem un silo.',
                'cūkas' => 'Cūkas ir omnvori un ēd graudus, dārzeņus, augļus un dažreiz arī gaļu.',
                'aitas' => 'Aitas ganās ar zāli, sienu un dažreiz arī ar graudiem kā miežiem un auzām.',
                'kazi' => 'Kazi ēd dažādus augus, tostarp zāli, lapas un krūmus.',
                'zosis' => 'Zoses ēd diētu bagātu ar graudiem, kukaiņiem, ūdensaugiem un mazām zivīm.',
                'cāļi' => 'Cāļi ir omnvori un ēd graudus, sēklas, kukaiņus un pat mazus grauzējus.'
            ],
        ],
        
        'species' => [
            'slug' => 'suga',
        ],
    ],
    'footer' => 'Source of information on what you can or cannot feed to your pets, provided by the community.',
    'section' => [
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