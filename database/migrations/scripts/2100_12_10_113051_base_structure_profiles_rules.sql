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
        _rule57 uuid := uuid_generate_v4();
        _rule58 uuid := uuid_generate_v4();
        _rule59 uuid := uuid_generate_v4();
        _rule60 uuid := uuid_generate_v4();
        _rule61 uuid := uuid_generate_v4();
        _rule62 uuid := uuid_generate_v4();
        _rule63 uuid := uuid_generate_v4();
        _rule64 uuid := uuid_generate_v4();
        _rule65 uuid := uuid_generate_v4();
        _rule66 uuid := uuid_generate_v4();
        _rule67 uuid := uuid_generate_v4();
        _rule68 uuid := uuid_generate_v4();
        _rule69 uuid := uuid_generate_v4();
        _rule70 uuid := uuid_generate_v4();
        _rule71 uuid := uuid_generate_v4();
        _rule72 uuid := uuid_generate_v4();
        _rule73 uuid := uuid_generate_v4();
        _rule74 uuid := uuid_generate_v4();
        _rule75 uuid := uuid_generate_v4();

    BEGIN
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
            (_rule14, 'PROFILES_ADMIN_CHURCH_VIEW', 'PROFILES_ADMIN_CHURCH', 'VIEW'),
            (_rule15, 'PROFILES_ADMIN_MODULE_VIEW', 'PROFILES_ADMIN_MODULE', 'VIEW'),
            (_rule16, 'PROFILES_ASSISTANT_VIEW', 'PROFILES_ASSISTANT', 'VIEW'),

            (_rule17, 'CITIES_VIEW', 'CITIES', 'VIEW' ),
            (_rule18, 'STATES_VIEW', 'STATES', 'VIEW'),

            (_rule19, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_VIEW',         'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER', 'VIEW'),
            (_rule20, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_DETAILS_VIEW', 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_DETAILS', 'VIEW'),
            (_rule21, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_INSERT',       'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER', 'INSERT'),
            (_rule22, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_UPDATE',       'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER', 'UPDATE'),
            (_rule23, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_DELETE',       'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER', 'DELETE'),
            (_rule24, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_IMAGE_UPLOAD', 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_IMAGE', 'UPLOAD'),

            (_rule25, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_VIEW',         'MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH', 'VIEW'),
            (_rule26, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_DETAILS_VIEW', 'MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_DETAILS', 'VIEW'),
            (_rule27, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_UPDATE',       'MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH', 'UPDATE'),
            (_rule28, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_IMAGE_UPLOAD', 'MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_IMAGE', 'UPLOAD'),

            (_rule29, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MODULE_VIEW',         'MEMBERSHIP_MODULE_CHURCH_ADMIN_MODULE', 'VIEW'),
            (_rule30, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MODULE_DETAILS_VIEW', 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MODULE_DETAILS', 'VIEW'),

            (_rule31, 'MEMBERSHIP_MODULE_CHURCH_ASSISTANT_VIEW', 'MEMBERSHIP_MODULE_CHURCH_ASSISTANT', 'VIEW'),
            (_rule32, 'MEMBERSHIP_MODULE_CHURCH_ASSISTANT_DETAILS_VIEW', 'MEMBERSHIP_MODULE_CHURCH_ASSISTANT_DETAILS', 'VIEW'),

            (_rule33, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_MEMBER_RELATIONSHIP_DELETE', 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_MEMBER_RELATIONSHIP', 'DELETE'),
            (_rule34, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_MEMBER_RELATIONSHIP_DELETE', 'MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_MEMBER_RELATIONSHIP', 'DELETE'),

            (_rule35, 'MEMBERSHIP_MODULE_CHURCH_VIEW',         'MEMBERSHIP_MODULE_CHURCH', 'VIEW'),
            (_rule36, 'MEMBERSHIP_MODULE_CHURCH_DETAILS_VIEW', 'MEMBERSHIP_MODULE_CHURCH_DETAILS', 'VIEW'),
            (_rule37, 'MEMBERSHIP_MODULE_CHURCH_INSERT',       'MEMBERSHIP_MODULE_CHURCH', 'INSERT'),
            (_rule38, 'MEMBERSHIP_MODULE_CHURCH_UPDATE',       'MEMBERSHIP_MODULE_CHURCH', 'UPDATE'),

            (_rule40, 'MEMBERSHIP_MODULE_MEMBERS_DETAILS_VIEW', 'MEMBERSHIP_MODULE_MEMBERS_DETAILS', 'VIEW'),
            (_rule41, 'MEMBERSHIP_MODULE_MEMBERS_INSERT', 'MEMBERSHIP_MODULE_MEMBERS', 'INSERT'),
            (_rule42, 'MEMBERSHIP_MODULE_MEMBERS_UPDATE', 'MEMBERSHIP_MODULE_MEMBERS', 'UPDATE'),

            (_rule43, 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_DETAILS_VIEW', 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_DETAILS', 'VIEW'),
            (_rule44, 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_INSERT',       'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER', 'INSERT'),
            (_rule45, 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_UPDATE',       'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER', 'UPDATE'),
            (_rule46, 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_IMAGE_UPLOAD', 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_IMAGE', 'UPLOAD'),

            (_rule47, 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_DETAILS_VIEW', 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_DETAILS', 'VIEW'),
            (_rule48, 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_INSERT',       'MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH', 'INSERT'),
            (_rule49, 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_UPDATE',       'MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH', 'UPDATE'),
            (_rule50, 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_IMAGE_UPLOAD', 'MEMBERSHIP_MODULE_MEMBERS_CHURCH_MASTER_IMAGE', 'UPLOAD'),

            (_rule51, 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_DETAILS_VIEW', 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_DETAILS', 'VIEW'),
            (_rule52, 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_INSERT',       'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE', 'INSERT'),
            (_rule53, 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_UPDATE',       'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE', 'UPDATE'),
            (_rule54, 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_IMAGE_UPLOAD', 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_IMAGE', 'UPLOAD'),

            (_rule55, 'MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_DETAILS_VIEW', 'MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_DETAILS', 'VIEW'),
            (_rule56, 'MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_INSERT',       'MEMBERSHIP_MODULE_MEMBERS_ASSISTANT', 'INSERT'),
            (_rule57, 'MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_UPDATE',       'MEMBERSHIP_MODULE_MEMBERS_ASSISTANT', 'UPDATE'),
            (_rule58, 'MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_IMAGE_UPLOAD', 'MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_IMAGE', 'UPLOAD'),

            (_rule59, 'USERS_ADMIN_MASTER_UPDATE_STATUS', 'USERS_ADMIN_MASTER_UPDATE_STATUS', 'UPDATE'),
            (_rule60, 'USERS_ADMIN_CHURCH_UPDATE_STATUS', 'USERS_ADMIN_CHURCH_UPDATE_STATUS', 'UPDATE'),

            (_rule61, 'MEMBERSHIP_MODULE_MEMBERS_PROFILES_FILTER_VIEW', 'MEMBERSHIP_MODULE_MEMBERS_PROFILES_FILTER', 'VIEW'),
            (_rule62, 'MEMBERSHIP_MODULE_MEMBERS_CHURCHES_FILTER_ADMIN_MASTER_VIEW', 'MEMBERSHIP_MODULE_MEMBERS_CHURCHES_FILTER_ADMIN_MASTER', 'VIEW'),
            (_rule63, 'MEMBERSHIP_MODULE_MEMBERS_CHURCHES_FILTER_VIEW', 'MEMBERSHIP_MODULE_MEMBERS_CHURCHES_FILTER', 'VIEW'),

            (_rule64, 'MODULES_VIEW', 'MODULES', 'VIEW'),

            (_rule65, 'USERS_EMAIL_ALREADY_EXISTS_VERIFICATION_VIEW', 'USERS_EMAIL_ALREADY_EXISTS_VERIFICATION', 'VIEW'),

            (_rule66, 'USERS_IMAGE_UPLOAD_ADMIN_MASTER', 'USERS_IMAGE_UPLOAD_ADMIN_MASTER', 'UPLOAD'),
            (_rule67, 'USERS_IMAGE_UPLOAD_ADMIN_CHURCH', 'USERS_IMAGE_UPLOAD_ADMIN_CHURCH', 'UPLOAD'),
            (_rule68, 'USERS_IMAGE_UPLOAD_ADMIN_MODULE', 'USERS_IMAGE_UPLOAD_ADMIN_MODULE', 'UPLOAD'),
            (_rule69, 'USERS_IMAGE_UPLOAD_ADMIN_ASSISTANT', 'USERS_IMAGE_UPLOAD_ADMIN_ASSISTANT', 'UPLOAD'),

            (_rule70, 'USERS_TECHNICAL_SUPPORT_UPDATE_STATUS', 'USERS_TECHNICAL_SUPPORT_UPDATE_STATUS', 'UPDATE'),
            (_rule71, 'USERS_ADMIN_MODULE_UPDATE_STATUS', 'USERS_ADMIN_MODULE_UPDATE_STATUS', 'UPDATE'),

            (_rule72, 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_VIEW', 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER', 'VIEW'),
            (_rule73, 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_VIEW', 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH', 'VIEW'),
            (_rule74, 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_VIEW', 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE', 'VIEW'),
            (_rule75, 'MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_VIEW',    'MEMBERSHIP_MODULE_MEMBERS_ASSISTANT',    'VIEW');


        INSERT INTO users.modules_rules (rule_id, module_id)
        VALUES
            (_module_rule1, _module1),
            (_module_rule2, _module2),
            (_module_rule3, _module3),
            (_module_rule4, _module4),
            (_module_rule5, _module5),
            (_module_rule6, _module6),
            (_module_rule7, _module7);

        INSERT INTO users.profile_types (id, description, unique_name)
        VALUES
            (
                _profile_type1,
                'Administrativo',
                'ADMINISTRATIVE'
            ),
            (
                _profile_type2,
                'Membresia',
                'MEMBERSHIP'
            ),
            (
                _profile_type3,
                'Usuário Comum',
                'COMMON_USER'
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
                _profile_type1,
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
                'Admin Módulo',
                'ADMIN_MODULE'
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
            (_profile1, _rule17),
            (_profile1, _rule18),
            (_profile1, _rule19),
            (_profile1, _rule20),
            (_profile1, _rule21),
            (_profile1, _rule22),
            (_profile1, _rule23),
            (_profile1, _rule24),
            (_profile1, _rule33),
            (_profile1, _rule35),
            (_profile1, _rule36),
            (_profile1, _rule37),
            (_profile1, _rule38),
            (_profile1, _rule40),
            (_profile1, _rule41),
            (_profile1, _rule42),
            (_profile1, _rule43),
            (_profile1, _rule44),
            (_profile1, _rule45),
            (_profile1, _rule46),
            (_profile1, _rule59),
            (_profile1, _rule61),
            (_profile1, _rule62),
            (_profile1, _rule64),
            (_profile1, _rule65),
            (_profile1, _rule66),
            (_profile1, _rule70),
            (_profile1, _rule72),


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
            (_profile2, _rule17),
            (_profile2, _rule18),
            (_profile2, _rule19),
            (_profile2, _rule20),
            (_profile2, _rule21),
            (_profile2, _rule22),
            (_profile2, _rule23),
            (_profile2, _rule24),
            (_profile2, _rule33),
            (_profile2, _rule35),
            (_profile2, _rule36),
            (_profile2, _rule37),
            (_profile2, _rule38),
            (_profile2, _rule40),
            (_profile2, _rule41),
            (_profile2, _rule42),
            (_profile2, _rule43),
            (_profile2, _rule44),
            (_profile2, _rule45),
            (_profile2, _rule46),
            (_profile2, _rule59),
            (_profile2, _rule61),
            (_profile2, _rule62),
            (_profile2, _rule64),
            (_profile2, _rule65),
            (_profile2, _rule66),
            (_profile2, _rule72),


            -- ADMIN CHURCH
            (_profile3, _rule1),
            (_profile3, _rule14),
            (_profile3, _rule17),
            (_profile3, _rule18),
            (_profile3, _rule25),
            (_profile3, _rule26),
            (_profile3, _rule27),
            (_profile3, _rule28),
            (_profile3, _rule34),
            (_profile3, _rule35),
            (_profile3, _rule36),
            (_profile3, _rule38),
            (_profile3, _rule40),
            (_profile3, _rule41),
            (_profile3, _rule42),
            (_profile3, _rule47),
            (_profile3, _rule48),
            (_profile3, _rule49),
            (_profile3, _rule50),
            (_profile3, _rule60),
            (_profile3, _rule61),
            (_profile3, _rule63),
            (_profile3, _rule64),
            (_profile3, _rule65),
            (_profile3, _rule67),
            (_profile3, _rule73),

            -- ADMIN MODULE
            (_profile4, _rule1),
            (_profile4, _rule15),
            (_profile4, _rule17),
            (_profile4, _rule18),
            (_profile4, _rule29),
            (_profile4, _rule30),
            (_profile4, _rule35),
            (_profile4, _rule36),
            (_profile4, _rule40),
            (_profile4, _rule41),
            (_profile4, _rule42),
            (_profile4, _rule51),
            (_profile4, _rule52),
            (_profile4, _rule53),
            (_profile4, _rule54),
            (_profile4, _rule61),
            (_profile4, _rule63),
            (_profile4, _rule64),
            (_profile4, _rule65),
            (_profile4, _rule68),
            (_profile4, _rule71),
            (_profile4, _rule74),

            -- ASSISTANT
            (_profile5, _rule1),
            (_profile5, _rule16),
            (_profile5, _rule17),
            (_profile5, _rule18),
            (_profile5, _rule31),
            (_profile5, _rule32),
            (_profile5, _rule35),
            (_profile5, _rule36),
            (_profile5, _rule40),
            (_profile5, _rule41),
            (_profile5, _rule42),
            (_profile5, _rule55),
            (_profile5, _rule56),
            (_profile5, _rule57),
            (_profile5, _rule58),
            (_profile5, _rule61),
            (_profile5, _rule63),
            (_profile5, _rule64),
            (_profile5, _rule65),
            (_profile5, _rule69),
            (_profile5, _rule75);

        -- MEMBER
    END $$;
