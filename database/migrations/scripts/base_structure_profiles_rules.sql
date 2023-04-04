DO $$
    DECLARE
        -- PROFILE TYPES
        _profile_type1 uuid := uuid_generate_v4();
        _profile_type2 uuid := uuid_generate_v4();
        _profile_type3 uuid := uuid_generate_v4();

        -- PROFILES
        _profile1 uuid := uuid_generate_v4();
        _profile2 uuid := uuid_generate_v4();
        _profile3 uuid := uuid_generate_v4();
        _profile4 uuid := uuid_generate_v4();
        _profile5 uuid := uuid_generate_v4();
        _profile6 uuid := uuid_generate_v4();
        _profile7 uuid := uuid_generate_v4();

        -- MODULES
        _module1 uuid := uuid_generate_v4();
        _module2 uuid := uuid_generate_v4();
        _module3 uuid := uuid_generate_v4();
        _module4 uuid := uuid_generate_v4();
        _module5 uuid := uuid_generate_v4();
        _module6 uuid := uuid_generate_v4();
        _module7 uuid := uuid_generate_v4();

        -- RULES
        _module_rule1 uuid := uuid_generate_v4();
        _module_rule2 uuid := uuid_generate_v4();
        _module_rule3 uuid := uuid_generate_v4();
        _module_rule4 uuid := uuid_generate_v4();
        _module_rule5 uuid := uuid_generate_v4();
        _module_rule6 uuid := uuid_generate_v4();
        _module_rule7 uuid := uuid_generate_v4();

        _rule1 uuid := uuid_generate_v4();
        _rule2 uuid := uuid_generate_v4();
        _rule3 uuid := uuid_generate_v4();
        _rule4 uuid := uuid_generate_v4();
        _rule5 uuid := uuid_generate_v4();
        _rule6 uuid := uuid_generate_v4();
        _rule7 uuid := uuid_generate_v4();
        _rule8 uuid := uuid_generate_v4();
        _rule9 uuid := uuid_generate_v4();
        _rule10 uuid := uuid_generate_v4();
        _rule11 uuid := uuid_generate_v4();
        _rule12 uuid := uuid_generate_v4();
        _rule13 uuid := uuid_generate_v4();
        _rule14 uuid := uuid_generate_v4();
        _rule15 uuid := uuid_generate_v4();
        _rule16 uuid := uuid_generate_v4();
        _rule17 uuid := uuid_generate_v4();
        _rule18 uuid := uuid_generate_v4();
        _rule19 uuid := uuid_generate_v4();
        _rule20 uuid := uuid_generate_v4();
        _rule21 uuid := uuid_generate_v4();
        _rule22 uuid := uuid_generate_v4();
        _rule23 uuid := uuid_generate_v4();
        _rule24 uuid := uuid_generate_v4();
        _rule25 uuid := uuid_generate_v4();
        _rule26 uuid := uuid_generate_v4();
        _rule27 uuid := uuid_generate_v4();
        _rule28 uuid := uuid_generate_v4();
        _rule29 uuid := uuid_generate_v4();
        _rule30 uuid := uuid_generate_v4();
        _rule31 uuid := uuid_generate_v4();
        _rule32 uuid := uuid_generate_v4();
        _rule33 uuid := uuid_generate_v4();
        _rule34 uuid := uuid_generate_v4();
        _rule35 uuid := uuid_generate_v4();
        _rule36 uuid := uuid_generate_v4();
        _rule37 uuid := uuid_generate_v4();
        _rule38 uuid := uuid_generate_v4();

    BEGIN
        INSERT INTO module.modules (id, module_description, module_unique_name, active)
        VALUES
            (_module1, 'Usuários', 'USERS', true),
            (_module2, 'Financeiro', 'FINANCE', true),
            (_module3, 'Membresia', 'MEMBERS', true),
            (_module4, 'Livraria', 'BOOKSTORE', true),
            (_module5, 'Grupos', 'GROUPS', true),
            (_module6, 'Agenda', 'SCHEDULE', true),
            (_module7, 'Patrimônio', 'PATRIMONY', true);

        INSERT INTO users.rules (id,description, subject, action)
        VALUES
            (_module_rule1, 'USERS_MODULE_VIEW', 'USERS_MODULE', 'VIEW'),
            (_module_rule2, 'FINANCE_MODULE_VIEW', 'FINANCE_MODULE', 'VIEW'),
            (_module_rule3, 'MEMBERS_MODULE_VIEW', 'MEMBERS_MODULE', 'VIEW'),
            (_module_rule4, 'BOOKSTORE_MODULE_VIEW', 'BOOKSTORE_MODULE', 'VIEW'),
            (_module_rule5, 'GROUPS_MODULE_VIEW', 'GROUPS_MODULE', 'VIEW'),
            (_module_rule6, 'SCHEDULE_MODULE_VIEW', 'SCHEDULE_MODULE', 'VIEW'),
            (_module_rule7, 'PATRIMONY_MODULE_VIEW', 'PATRIMONY_MODULE', 'VIEW'),

            (_rule1, 'ROOT', 'ROOT', 'VIEW'),

            (_rule2, 'USERS_VIEW', 'USERS', 'VIEW'),

            (_rule3, 'ADMIN_USERS_VIEW', 'ADMIN_USERS_VIEW', 'VIEW'),
            (_rule4, 'ADMIN_USERS_INSERT', 'ADMIN_USERS_INSERT', 'INSERT'),
            (_rule5, 'ADMIN_USERS_UPDATE', 'ADMIN_USERS_UPDATE', 'UPDATE'),
            (_rule6, 'ADMIN_USERS_DELETE', 'ADMIN_USERS_DELETE', 'DELETE'),

            (_rule7, 'ADMIN_USERS_ADMIN_MASTER_VIEW', 'ADMIN_USERS_ADMIN_MASTER', 'VIEW'),
            (_rule8, 'ADMIN_USERS_ADMIN_MASTER_INSERT', 'ADMIN_USERS_ADMIN_MASTER', 'INSERT'),
            (_rule9, 'ADMIN_USERS_ADMIN_MASTER_UPDATE', 'ADMIN_USERS_ADMIN_MASTER', 'UPDATE'),

            (_rule10, 'ADMIN_USERS_ADMIN_CHURCH_VIEW', 'ADMIN_USERS_ADMIN_CHURCH', 'VIEW'),
            (_rule11, 'ADMIN_USERS_ADMIN_CHURCH_INSERT', 'ADMIN_USERS_ADMIN_CHURCH', 'INSERT'),
            (_rule12, 'ADMIN_USERS_ADMIN_CHURCH_UPDATE', 'ADMIN_USERS_ADMIN_CHURCH', 'UPDATE'),
            (_rule13, 'ADMIN_USERS_ADMIN_CHURCH_DELETE', 'ADMIN_USERS_ADMIN_CHURCH', 'DELETE'),

            (_rule14, 'ADMIN_USERS_ADMIN_DEPARTMENT_VIEW', 'ADMIN_USERS_ADMIN_DEPARTMENT', 'VIEW'),
            (_rule15, 'ADMIN_USERS_ADMIN_DEPARTMENT_INSERT', 'ADMIN_USERS_ADMIN_DEPARTMENT', 'INSERT'),
            (_rule16, 'ADMIN_USERS_ADMIN_DEPARTMENT_UPDATE', 'ADMIN_USERS_ADMIN_DEPARTMENT', 'UPDATE'),
            (_rule17, 'ADMIN_USERS_ADMIN_DEPARTMENT_DELETE', 'ADMIN_USERS_ADMIN_DEPARTMENT', 'DELETE'),

            (_rule18, 'ADMIN_USERS_ASSISTANT_VIEW', 'ADMIN_USERS_ADMIN_ASSISTANT', 'VIEW'),
            (_rule19, 'ADMIN_USERS_ASSISTANT_INSERT', 'ADMIN_USERS_ADMIN_ASSISTANT', 'INSERT'),
            (_rule20, 'ADMIN_USERS_ASSISTANT_UPDATE', 'ADMIN_USERS_ADMIN_ASSISTANT', 'UPDATE'),
            (_rule21, 'ADMIN_USERS_ASSISTANT_DELETE', 'ADMIN_USERS_ADMIN_ASSISTANT', 'DELETE'),

            (_rule22, 'ADMIN_USERS_MEMBER_VIEW', 'ADMIN_USERS_ADMIN_MEMBER', 'VIEW'),
            (_rule23, 'ADMIN_USERS_MEMBER_INSERT', 'ADMIN_USERS_ADMIN_MEMBER', 'INSERT'),
            (_rule24, 'ADMIN_USERS_MEMBER_UPDATE', 'ADMIN_USERS_ADMIN_MEMBER', 'UPDATE'),
            (_rule25, 'ADMIN_USERS_MEMBER_DELETE', 'ADMIN_USERS_ADMIN_MEMBER', 'DELETE'),

            (_rule26, 'PROFILES_ADMIN_MASTER_VIEW', 'PROFILES_ADMIN_MASTER', 'VIEW'),
            (_rule27, 'PROFILES_ADMIN_CHURCH_VIEW', 'PROFILES_ADMIN_MASTER', 'VIEW'),
            (_rule28, 'PROFILES_ADMIN_DEPARTMENT_VIEW', 'PROFILES_ADMIN_MASTER', 'VIEW'),
            (_rule29, 'PROFILES_ASSISTANT_VIEW', 'PROFILES_ADMIN_MASTER', 'VIEW'),
            (_rule30, 'PROFILES_MEMBER_VIEW', 'PROFILES_ADMIN_MASTER', 'VIEW'),

            (_rule31, 'CITIES_VIEW', 'CITIES', 'VIEW' ),
            (_rule32, 'STATES_VIEW', 'STATES', 'VIEW'),

            (_rule33, 'COUNT_USERS_ADMIN_MASTER_VIEW',     'COUNT_USERS_ADMIN_MASTER', 'VIEW'),
            (_rule34, 'COUNT_USERS_ADMIN_CHURCH_VIEW',     'COUNT_USERS_ADMIN_CHURCH', 'VIEW'),
            (_rule35, 'COUNT_USERS_ADMIN_DEPARTMENT_VIEW', 'COUNT_USERS_ADMIN_DEPARTMENT', 'VIEW'),
            (_rule36, 'COUNT_USERS_ASSISTANT_VIEW',        'COUNT_USERS_ASSISTANT', 'VIEW');

        INSERT INTO users.modules_rules (rule_id, module_id)
        VALUES
            (_module_rule1, _module1),
            (_module_rule2, _module2),
            (_module_rule3, _module3),
            (_module_rule4, _module4),
            (_module_rule5, _module5),
            (_module_rule6, _module6),
            (_module_rule7, _module7);

        INSERT INTO users.profile_types (id, description)
        VALUES
            (
                _profile_type1,
                'Suporte'
            ),
            (
                _profile_type2,
                'Administrativo'
            ),
            (
                _profile_type3,
                'Usuário'
            );

        INSERT INTO users.profiles (id, profile_type_id, description, unique_name)
        VALUES
            (
                _profile1,
                _profile_type1,
                'Suporte Técnico',
                'TECHNICAL_SUPPORT'
            ),
            (
                _profile2,
                _profile_type2,
                'Admin Master',
                'ADMIN_MASTER'
            ),
            (
                _profile3,
                _profile_type2,
                'Admin Igreja',
                'ADMIN_CHURCH'
            ),
            (
                _profile4,
                _profile_type2,
                'Admin Departamento',
                'ADMIN_DEPARTMENT'
            ),
            (
                _profile5,
                _profile_type2,
                'Auxiliar',
                'ASSISTANT'
            ),
            (
                _profile6,
                _profile_type2,
                'Membro',
                'MEMBER'
            ),
            (
                _profile7,
                _profile_type3,
                'Usuário Site',
                'WEB_SITE_USER'
            );

        INSERT INTO users.profiles_rules (profile_id, rule_id)
        VALUES
            -- ADMIN MASTER
            (_profile2, _rule1),
            (_profile2, _rule2),
            (_profile2, _rule3),
            (_profile2, _rule4),
            (_profile2, _rule5),
            (_profile2, _rule6),
            (_profile2, _rule7),
            (_profile2, _rule8),
            (_profile2, _rule9),
            (_profile2, _rule10),
            (_profile2, _rule11),
            (_profile2, _rule12),
            (_profile2, _rule13),
            (_profile2, _rule14),
            (_profile2, _rule15),
            (_profile2, _rule16),
            (_profile2, _rule17),
            (_profile2, _rule18),
            (_profile2, _rule19),
            (_profile2, _rule20),
            (_profile2, _rule21),
            (_profile2, _rule22),
            (_profile2, _rule23),
            (_profile2, _rule24),
            (_profile2, _rule25),
            (_profile2, _rule26),
            (_profile2, _rule27),
            (_profile2, _rule28),
            (_profile2, _rule29),
            (_profile2, _rule30),
            (_profile2, _rule31),
            (_profile2, _rule32),
            (_profile2, _rule33),
            (_profile2, _rule34),
            (_profile2, _rule35),
            (_profile2, _rule36),

            -- ADMIN CHURCH
            (_profile3, _rule34),
            (_profile3, _rule35),
            (_profile3, _rule36),

            -- ADMIN DEPARTMENT
            (_profile4, _rule35),
            (_profile4, _rule36),

            -- ASSISTANT
            (_profile5, _rule36);

    END $$;
