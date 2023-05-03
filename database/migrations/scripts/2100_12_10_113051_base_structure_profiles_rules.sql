DO $$
    DECLARE
        -- PROFILE TYPES
        _profile_type1 uuid := uuid_generate_v4();
        _profile_type2 uuid := uuid_generate_v4();
        _profile_type3 uuid := uuid_generate_v4();
        _profile_type4 uuid := uuid_generate_v4();

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
        _rule39 uuid := uuid_generate_v4();
        _rule40 uuid := uuid_generate_v4();
        _rule41 uuid := uuid_generate_v4();
        _rule42 uuid := uuid_generate_v4();
        _rule43 uuid := uuid_generate_v4();
        _rule44 uuid := uuid_generate_v4();
        _rule45 uuid := uuid_generate_v4();
        _rule46 uuid := uuid_generate_v4();
        _rule47 uuid := uuid_generate_v4();
        _rule48 uuid := uuid_generate_v4();
        _rule49 uuid := uuid_generate_v4();
        _rule50 uuid := uuid_generate_v4();
        _rule51 uuid := uuid_generate_v4();
        _rule52 uuid := uuid_generate_v4();
        _rule53 uuid := uuid_generate_v4();
        _rule54 uuid := uuid_generate_v4();
        _rule55 uuid := uuid_generate_v4();
        _rule56 uuid := uuid_generate_v4();

    BEGIN
        INSERT INTO membership.member_types(unique_name, description)
        values
            ('RESPONSIBLE', 'Responsável'),
            ('COMMON_MEMBER', 'Membro Comum');

        INSERT INTO module.modules (id, module_description, module_unique_name, active)
        VALUES
            (_module1, 'Usuários', 'USERS', true),
            (_module2, 'Financeiro', 'FINANCE', true),
            (_module3, 'Membresia', 'MEMBERSHIP', true),
            (_module4, 'Livraria', 'STORE', true),
            (_module5, 'Grupos', 'GROUPS', true),
            (_module6, 'Agenda', 'SCHEDULE', true),
            (_module7, 'Patrimônio', 'PATRIMONY', true);

        INSERT INTO users.rules (id,description, subject, action)
        VALUES
            (_module_rule1, 'USERS_MODULE_VIEW', 'USERS_MODULE', 'VIEW'),
            (_module_rule2, 'FINANCE_MODULE_VIEW', 'FINANCE_MODULE', 'VIEW'),
            (_module_rule3, 'MEMBERSHIP_MODULE_VIEW', 'MEMBERSHIP_MODULE', 'VIEW'),
            (_module_rule4, 'STORE_MODULE_VIEW', 'STORE_MODULE', 'VIEW'),
            (_module_rule5, 'GROUPS_MODULE_VIEW', 'GROUPS_MODULE', 'VIEW'),
            (_module_rule6, 'SCHEDULE_MODULE_VIEW', 'SCHEDULE_MODULE', 'VIEW'),
            (_module_rule7, 'PATRIMONY_MODULE_VIEW', 'PATRIMONY_MODULE', 'VIEW'),

            (_rule1, 'ROOT', 'ROOT', 'VIEW'),

            (_rule2, 'ADMIN_USERS_VIEW', 'ADMIN_USERS', 'VIEW'),
            (_rule3, 'ADMIN_USERS_INSERT', 'ADMIN_USERS', 'INSERT'),
            (_rule4, 'ADMIN_USERS_UPDATE', 'ADMIN_USERS', 'UPDATE'),
            (_rule5, 'ADMIN_USERS_DELETE', 'ADMIN_USERS', 'DELETE'),

            (_rule6, 'ADMIN_USERS_SUPPORT_VIEW', 'ADMIN_USERS_SUPPORT', 'VIEW'),
            (_rule7, 'ADMIN_USERS_SUPPORT_INSERT', 'ADMIN_USERS_SUPPORT', 'INSERT'),
            (_rule8, 'ADMIN_USERS_SUPPORT_UPDATE', 'ADMIN_USERS_SUPPORT', 'UPDATE'),

            (_rule9, 'ADMIN_USERS_ADMIN_MASTER_VIEW', 'ADMIN_USERS_ADMIN_MASTER', 'VIEW'),
            (_rule10, 'ADMIN_USERS_ADMIN_MASTER_INSERT', 'ADMIN_USERS_ADMIN_MASTER', 'INSERT'),
            (_rule11, 'ADMIN_USERS_ADMIN_MASTER_UPDATE', 'ADMIN_USERS_ADMIN_MASTER', 'UPDATE'),

            (_rule12, 'PROFILES_SUPPORT_VIEW', 'PROFILES_SUPPORT', 'VIEW'),
            (_rule13, 'PROFILES_ADMIN_MASTER_VIEW', 'PROFILES_ADMIN_MASTER', 'VIEW'),

            (_rule14, 'CITIES_VIEW', 'CITIES', 'VIEW' ),
            (_rule15, 'STATES_VIEW', 'STATES', 'VIEW');


--             (_rule37, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_VIEW',         'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER', 'VIEW'),
--             (_rule38, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_DETAILS_VIEW', 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_DETAILS', 'VIEW'),
--             (_rule39, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_INSERT',       'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER', 'INSERT'),
--             (_rule40, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_UPDATE',       'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER', 'UPDATE'),
--             (_rule41, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_DELETE',       'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER', 'DELETE'),
--             (_rule42, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_IMAGE_UPLOAD', 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_IMAGE', 'UPLOAD'),
--
--             (_rule43, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_VIEW',         'MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH', 'VIEW'),
--             (_rule44, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_DETAILS_VIEW', 'MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_DETAILS', 'VIEW'),
--             (_rule45, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_UPDATE',       'MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH', 'UPDATE'),
--             (_rule46, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_IMAGE_UPLOAD', 'MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_IMAGE', 'UPLOAD'),
--
--             (_rule47, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MODULE_VIEW',         'MEMBERSHIP_MODULE_CHURCH_ADMIN_MODULE', 'VIEW'),
--             (_rule48, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MODULE_DETAILS_VIEW', 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MODULE_DETAILS', 'VIEW'),
--
--             (_rule49, 'MEMBERSHIP_MODULE_CHURCH_ASSISTANT_VIEW', 'MEMBERSHIP_MODULE_CHURCH_ASSISTANT', 'VIEW'),
--             (_rule50, 'MEMBERSHIP_MODULE_CHURCH_ASSISTANT_DETAILS_VIEW', 'MEMBERSHIP_MODULE_CHURCH_ASSISTANT_DETAILS', 'VIEW'),
--
--             (_rule51, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_USER_RELATIONSHIP_DELETE', 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_USER_RELATIONSHIP', 'DELETE'),
--             (_rule52, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_USER_RELATIONSHIP_DELETE', 'MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_USER_RELATIONSHIP', 'DELETE'),
--
--             (_rule53, 'MEMBERSHIP_MODULE_CHURCH_VIEW',         'MEMBERSHIP_MODULE_CHURCH', 'VIEW'),
--             (_rule54, 'MEMBERSHIP_MODULE_CHURCH_DETAILS_VIEW', 'MEMBERSHIP_MODULE_CHURCH_DETAILS', 'VIEW'),
--             (_rule55, 'MEMBERSHIP_MODULE_CHURCH_INSERT',       'MEMBERSHIP_MODULE_CHURCH', 'INSERT'),
--             (_rule56, 'MEMBERSHIP_MODULE_CHURCH_UPDATE',       'MEMBERSHIP_MODULE_CHURCH', 'UPDATE');

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
                'Diretoria'
            ),
            (
                _profile_type4,
                'Usuário Comum'
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
                _profile_type3,
                'Admin Igreja',
                'ADMIN_CHURCH'
            ),
            (
                _profile4,
                _profile_type3,
                'Admin Módulo',
                'ADMIN_MODULE'
            ),
            (
                _profile5,
                _profile_type3,
                'Auxiliar',
                'ASSISTANT'
            ),
            (
                _profile6,
                _profile_type4,
                'Membro',
                'MEMBER'
            ),
            (
                _profile7,
                _profile_type4,
                'VISITANTE',
                'VISITOR'
            );

        INSERT INTO users.profiles_rules (profile_id, rule_id)
        VALUES
            -- SUPPORT
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

            -- ADMIN MASTER
            (_profile2, _rule1),
            (_profile2, _rule2),
            (_profile2, _rule3),
            (_profile2, _rule4),
            (_profile2, _rule5),
            (_profile2, _rule9),
            (_profile2, _rule10),
            (_profile2, _rule11),
            (_profile2, _rule13),
            (_profile2, _rule14),
            (_profile2, _rule15);

            -- ADMIN CHURCH

            -- ADMIN MODULE

            -- ASSISTANT

            -- MEMBER
    END $$;
