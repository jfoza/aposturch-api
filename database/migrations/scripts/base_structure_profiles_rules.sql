DO $$
    DECLARE
        -- PROFILE TYPES
        _profile_type1 uuid := uuid_generate_v4();
        _profile_type2 uuid := uuid_generate_v4();

        -- PROFILES
        _profile1 uuid := uuid_generate_v4();
        _profile2 uuid := uuid_generate_v4();
        _profile3 uuid := uuid_generate_v4();

        -- MODULES
        _module1 uuid := uuid_generate_v4();
        _module2 uuid := uuid_generate_v4();
        _module3 uuid := uuid_generate_v4();
        _module4 uuid := uuid_generate_v4();
        _module5 uuid := uuid_generate_v4();
        _module6 uuid := uuid_generate_v4();

        -- RULES
        _module_rule1 uuid := uuid_generate_v4();
        _module_rule2 uuid := uuid_generate_v4();
        _module_rule3 uuid := uuid_generate_v4();
        _module_rule4 uuid := uuid_generate_v4();
        _module_rule5 uuid := uuid_generate_v4();
        _module_rule6 uuid := uuid_generate_v4();

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

    BEGIN
        INSERT INTO module.modules (id,description, active)
        VALUES
            (_module1, 'USERS', true),
            (_module2, 'FINANCE', true),
            (_module3, 'MEMBERS', true),
            (_module4, 'BOOKSTORE', true),
            (_module5, 'GROUPS', true),
            (_module6, 'SCHEDULE', true);

        INSERT INTO users.rules (id,description, subject, action)
        VALUES
            (_module_rule1, 'USERS_MODULE_VIEW', 'USERS_MODULE', 'VIEW'),
            (_module_rule2, 'FINANCE_MODULE_VIEW', 'FINANCE_MODULE', 'VIEW'),
            (_module_rule3, 'MEMBERS_MODULE_VIEW', 'MEMBERS_MODULE', 'VIEW'),
            (_module_rule4, 'BOOKSTORE_MODULE_VIEW', 'BOOKSTORE_MODULE', 'VIEW'),
            (_module_rule5, 'GROUPS_MODULE_VIEW', 'GROUPS_MODULE', 'VIEW'),
            (_module_rule6, 'SCHEDULE_MODULE_VIEW', 'SCHEDULE_MODULE', 'VIEW'),

            (_rule1, 'ROOT', 'ROOT', 'VIEW'),
            (_rule2, 'USERS_VIEW', 'USERS', 'VIEW'),
            (_rule3, 'ADMIN_USERS_VIEW', 'ADMIN_USERS_VIEW', 'VIEW'),
            (_rule4, 'ADMIN_USERS_INSERT', 'ADMIN_USERS_INSERT', 'INSERT'),
            (_rule5, 'ADMIN_USERS_UPDATE', 'ADMIN_USERS_UPDATE', 'UPDATE'),
            (_rule6, 'ADMIN_USERS_ADMIN_MASTER_VIEW', 'ADMIN_USERS_ADMIN_MASTER', 'VIEW'),
            (_rule7, 'ADMIN_USERS_ADMIN_MASTER_INSERT', 'ADMIN_USERS_ADMIN_MASTER', 'INSERT'),
            (_rule8, 'ADMIN_USERS_ADMIN_MASTER_UPDATE', 'ADMIN_USERS_ADMIN_MASTER', 'UPDATE'),
            (_rule9, 'ADMIN_USERS_EMPLOYEE_VIEW', 'ADMIN_USERS_EMPLOYEE', 'VIEW'),
            (_rule10, 'ADMIN_USERS_EMPLOYEE_INSERT', 'ADMIN_USERS_EMPLOYEE', 'INSERT'),
            (_rule11, 'ADMIN_USERS_EMPLOYEE_UPDATE', 'ADMIN_USERS_EMPLOYEE', 'UPDATE'),
            (_rule12, 'CUSTOMERS_VIEW', 'CUSTOMERS', 'VIEW'),
            (_rule13, 'CUSTOMERS_INSERT', 'CUSTOMERS', 'INSERT'),
            (_rule14, 'CUSTOMERS_UPDATE', 'CUSTOMERS', 'UPDATE'),
            (_rule15, 'CUSTOMERS_DELETE', 'CUSTOMERS', 'DELETE'),
            (_rule16, 'PROFILES_ADMIN_MASTER_VIEW', 'PROFILES_ADMIN_MASTER', 'VIEW'),
            (_rule17, 'PROFILES_EMPLOYEE_VIEW', 'PROFILES_EMPLOYEE', 'VIEW'),
            (_rule18, 'CITIES_VIEW', 'CITIES', 'VIEW' ),
            (_rule19, 'STATES_VIEW', 'STATES', 'VIEW');

        INSERT INTO users.modules_rules (rule_id, module_id)
        VALUES
            (_module_rule1, _module1),
            (_module_rule2, _module2),
            (_module_rule3, _module3),
            (_module_rule4, _module4),
            (_module_rule5, _module5),
            (_module_rule6, _module6);

        INSERT INTO users.profile_types (id, description)
        VALUES
            (
                _profile_type1,
                'Administrativo'
            ),
            (
                _profile_type2,
                'Cliente'
            );

        INSERT INTO users.profiles (id, profile_type_id, description, unique_name)
        VALUES
            (
                _profile1,
                _profile_type1,
                'Admin Master',
                'admin-master'
            ),
            (
                _profile2,
                _profile_type1,
                'Colaborador',
                'employee'
            ),
            (
                _profile3,
                _profile_type2,
                'Cliente',
                'customer'
            );

        INSERT INTO users.profiles_rules (profile_id, rule_id)
        VALUES
            -- ADMIN MASTER
            (_profile1, _rule1),
            (_profile1, _rule2),
            (_profile1, _rule3),
            (_profile1, _rule4),
            (_profile1, _rule5),
            (_profile1, _rule6),
            (_profile1, _rule7),
            (_profile1, _rule8),
            (_profile1, _rule9),
            (_profile1, _rule10),
            (_profile1, _rule11),
            (_profile1, _rule12),
            (_profile1, _rule13),
            (_profile1, _rule14),
            (_profile1, _rule15),
            (_profile1, _rule16),
            (_profile1, _rule17),
            (_profile1, _rule18),
            (_profile1, _rule19),

            (_profile2, _rule1),
            (_profile2, _rule2),
            (_profile2, _rule3),
            (_profile2, _rule4),
            (_profile2, _rule5),
            (_profile2, _rule9),
            (_profile2, _rule10),
            (_profile2, _rule11),
            (_profile2, _rule12),
            (_profile2, _rule13),
            (_profile2, _rule14),
            (_profile2, _rule15),
            (_profile2, _rule17),
            (_profile2, _rule18),
            (_profile2, _rule19);
    END $$;
