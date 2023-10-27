DO $$
    DECLARE
        _view_action   varchar := 'VIEW';
        _insert_action varchar := 'INSERT';
        _update_action varchar := 'UPDATE';
        _delete_action varchar := 'DELETE';
        _upload_action varchar := 'UPLOAD';

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
        _rule76 uuid := uuid_generate_v4();
        _rule77 uuid := uuid_generate_v4();
        _rule78 uuid := uuid_generate_v4();
        _rule79 uuid := uuid_generate_v4();
        _rule80 uuid := uuid_generate_v4();
        _rule81 uuid := uuid_generate_v4();
        _rule82 uuid := uuid_generate_v4();
        _rule83 uuid := uuid_generate_v4();
        _rule84 uuid := uuid_generate_v4();
        _rule85 uuid := uuid_generate_v4();
        _rule86 uuid := uuid_generate_v4();
        _rule87 uuid := uuid_generate_v4();
        _rule88 uuid := uuid_generate_v4();
        _rule89 uuid := uuid_generate_v4();
        _rule90 uuid := uuid_generate_v4();
        _rule91 uuid := uuid_generate_v4();
        _rule92 uuid := uuid_generate_v4();
        _rule93 uuid := uuid_generate_v4();
        _rule94 uuid := uuid_generate_v4();
        _rule95 uuid := uuid_generate_v4();

    BEGIN
        INSERT INTO module.modules (id, module_description, module_unique_name, active)
        VALUES
            (_module1, 'Usuários', 'USERS', true),
            (_module2, 'Financeiro', 'FINANCE', true),
            (_module3, 'Membresia', 'MEMBERSHIP', true),
            (_module4, 'Loja Virtual', 'STORE', true),
            (_module5, 'Grupos', 'GROUPS', true),
            (_module6, 'Agenda', 'SCHEDULE', true),
            (_module7, 'Patrimônio', 'PATRIMONY', true);

        INSERT INTO users.rules (id,description, subject, action)
        VALUES
            (_module_rule1, 'USERS_MODULE_VIEW', 'USERS_MODULE', _view_action),
            (_module_rule2, 'FINANCE_MODULE_VIEW', 'FINANCE_MODULE', _view_action),
            (_module_rule3, 'MEMBERSHIP_MODULE_VIEW', 'MEMBERSHIP_MODULE', _view_action),
            (_module_rule4, 'STORE_MODULE_VIEW', 'STORE_MODULE', _view_action),
            (_module_rule5, 'GROUPS_MODULE_VIEW', 'GROUPS_MODULE', _view_action),
            (_module_rule6, 'SCHEDULE_MODULE_VIEW', 'SCHEDULE_MODULE', _view_action),
            (_module_rule7, 'PATRIMONY_MODULE_VIEW', 'PATRIMONY_MODULE', _view_action),

            (_rule1, 'ROOT', 'ROOT', _view_action),

            (_rule2, 'ADMIN_USERS_VIEW', 'ADMIN_USERS', _view_action),
            (_rule3, 'ADMIN_USERS_INSERT', 'ADMIN_USERS', _insert_action),
            (_rule4, 'ADMIN_USERS_UPDATE', 'ADMIN_USERS', _update_action),
            (_rule5, 'ADMIN_USERS_DELETE', 'ADMIN_USERS', _delete_action),

            (_rule6, 'ADMIN_USERS_SUPPORT_VIEW', 'ADMIN_USERS_SUPPORT', _view_action),
            (_rule7, 'ADMIN_USERS_SUPPORT_INSERT', 'ADMIN_USERS_SUPPORT', _insert_action),
            (_rule8, 'ADMIN_USERS_SUPPORT_UPDATE', 'ADMIN_USERS_SUPPORT', _update_action),

            (_rule9, 'ADMIN_USERS_ADMIN_MASTER_VIEW', 'ADMIN_USERS_ADMIN_MASTER', _view_action),
            (_rule10, 'ADMIN_USERS_ADMIN_MASTER_INSERT', 'ADMIN_USERS_ADMIN_MASTER', _insert_action),
            (_rule11, 'ADMIN_USERS_ADMIN_MASTER_UPDATE', 'ADMIN_USERS_ADMIN_MASTER', _update_action),

            (_rule12, 'PROFILES_SUPPORT_VIEW', 'PROFILES_SUPPORT', _view_action),
            (_rule13, 'PROFILES_ADMIN_MASTER_VIEW', 'PROFILES_ADMIN_MASTER', _view_action),
            (_rule14, 'PROFILES_ADMIN_CHURCH_VIEW', 'PROFILES_ADMIN_CHURCH', _view_action),
            (_rule15, 'PROFILES_ADMIN_MODULE_VIEW', 'PROFILES_ADMIN_MODULE', _view_action),
            (_rule16, 'PROFILES_ASSISTANT_VIEW', 'PROFILES_ASSISTANT', _view_action),

            (_rule17, 'CITIES_VIEW', 'CITIES', _view_action ),
            (_rule18, 'STATES_VIEW', 'STATES', _view_action),

            (_rule19, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_VIEW',         'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER', _view_action),
            (_rule20, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_DETAILS_VIEW', 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_DETAILS', _view_action),
            (_rule21, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_INSERT',       'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER', _insert_action),
            (_rule22, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_UPDATE',       'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER', _update_action),
            (_rule23, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_DELETE',       'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER', _delete_action),
            (_rule24, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_IMAGE_UPLOAD', 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_IMAGE', _upload_action),

            (_rule25, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_VIEW',         'MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH', _view_action),
            (_rule26, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_DETAILS_VIEW', 'MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_DETAILS', _view_action),
            (_rule27, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_UPDATE',       'MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH', _update_action),
            (_rule28, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_IMAGE_UPLOAD', 'MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_IMAGE', _upload_action),

            (_rule29, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MODULE_VIEW',         'MEMBERSHIP_MODULE_CHURCH_ADMIN_MODULE', _view_action),
            (_rule30, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MODULE_DETAILS_VIEW', 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MODULE_DETAILS', _view_action),

            (_rule31, 'MEMBERSHIP_MODULE_CHURCH_ASSISTANT_VIEW', 'MEMBERSHIP_MODULE_CHURCH_ASSISTANT', _view_action),
            (_rule32, 'MEMBERSHIP_MODULE_CHURCH_ASSISTANT_DETAILS_VIEW', 'MEMBERSHIP_MODULE_CHURCH_ASSISTANT_DETAILS', _view_action),

            (_rule33, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_MEMBER_RELATIONSHIP_DELETE', 'MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_MEMBER_RELATIONSHIP', _delete_action),
            (_rule34, 'MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_MEMBER_RELATIONSHIP_DELETE', 'MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_MEMBER_RELATIONSHIP', _delete_action),

            (_rule35, 'MEMBERSHIP_MODULE_CHURCH_VIEW',         'MEMBERSHIP_MODULE_CHURCH', _view_action),
            (_rule36, 'MEMBERSHIP_MODULE_CHURCH_DETAILS_VIEW', 'MEMBERSHIP_MODULE_CHURCH_DETAILS', _view_action),
            (_rule37, 'MEMBERSHIP_MODULE_CHURCH_INSERT',       'MEMBERSHIP_MODULE_CHURCH', _insert_action),
            (_rule38, 'MEMBERSHIP_MODULE_CHURCH_UPDATE',       'MEMBERSHIP_MODULE_CHURCH', _update_action),
            (_rule39, 'MEMBERSHIP_MODULE_MEMBERS',             'MEMBERSHIP_MODULE_MEMBERS', _view_action),

            (_rule40, 'MEMBERSHIP_MODULE_MEMBERS_DETAILS_VIEW', 'MEMBERSHIP_MODULE_MEMBERS_DETAILS', _view_action),
            (_rule41, 'MEMBERSHIP_MODULE_MEMBERS_INSERT', 'MEMBERSHIP_MODULE_MEMBERS', _insert_action),
            (_rule42, 'MEMBERSHIP_MODULE_MEMBERS_UPDATE', 'MEMBERSHIP_MODULE_MEMBERS', _update_action),

            (_rule43, 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_DETAILS_VIEW', 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_DETAILS', _view_action),
            (_rule44, 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_INSERT',       'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER', _insert_action),
            (_rule45, 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_UPDATE',       'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER', _update_action),
            (_rule46, 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_IMAGE_UPLOAD', 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_IMAGE', _upload_action),

            (_rule47, 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_DETAILS_VIEW', 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_DETAILS', _view_action),
            (_rule48, 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_INSERT',       'MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH', _insert_action),
            (_rule49, 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_UPDATE',       'MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH', _update_action),
            (_rule50, 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_IMAGE_UPLOAD', 'MEMBERSHIP_MODULE_MEMBERS_CHURCH_MASTER_IMAGE', _upload_action),

            (_rule51, 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_DETAILS_VIEW', 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_DETAILS', _view_action),
            (_rule52, 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_INSERT',       'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE', _insert_action),
            (_rule53, 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_UPDATE',       'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE', _update_action),
            (_rule54, 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_IMAGE_UPLOAD', 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_IMAGE', _upload_action),

            (_rule55, 'MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_DETAILS_VIEW', 'MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_DETAILS', _view_action),
            (_rule56, 'MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_INSERT',       'MEMBERSHIP_MODULE_MEMBERS_ASSISTANT', _insert_action),
            (_rule57, 'MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_UPDATE',       'MEMBERSHIP_MODULE_MEMBERS_ASSISTANT', _update_action),
            (_rule58, 'MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_IMAGE_UPLOAD', 'MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_IMAGE', _upload_action),

            (_rule59, 'USERS_ADMIN_MASTER_UPDATE_STATUS', 'USERS_ADMIN_MASTER_UPDATE_STATUS', _update_action),
            (_rule60, 'USERS_ADMIN_CHURCH_UPDATE_STATUS', 'USERS_ADMIN_CHURCH_UPDATE_STATUS', _update_action),

            (_rule61, 'MEMBERSHIP_MODULE_MEMBERS_PROFILES_FILTER_VIEW', 'MEMBERSHIP_MODULE_MEMBERS_PROFILES_FILTER', _view_action),
            (_rule62, 'MEMBERSHIP_MODULE_MEMBERS_CHURCHES_FILTER_ADMIN_MASTER_VIEW', 'MEMBERSHIP_MODULE_MEMBERS_CHURCHES_FILTER_ADMIN_MASTER', _view_action),
            (_rule63, 'MEMBERSHIP_MODULE_MEMBERS_CHURCHES_FILTER_VIEW', 'MEMBERSHIP_MODULE_MEMBERS_CHURCHES_FILTER', _view_action),

            (_rule64, 'MODULES_VIEW', 'MODULES', _view_action),

            (_rule65, 'USERS_EMAIL_ALREADY_EXISTS_VERIFICATION_VIEW', 'USERS_EMAIL_ALREADY_EXISTS_VERIFICATION', _view_action),

            (_rule66, 'USERS_IMAGE_UPLOAD_ADMIN_MASTER', 'USERS_IMAGE_UPLOAD_ADMIN_MASTER', _upload_action),
            (_rule67, 'USERS_IMAGE_UPLOAD_ADMIN_CHURCH', 'USERS_IMAGE_UPLOAD_ADMIN_CHURCH', _upload_action),
            (_rule68, 'USERS_IMAGE_UPLOAD_ADMIN_MODULE', 'USERS_IMAGE_UPLOAD_ADMIN_MODULE', _upload_action),
            (_rule69, 'USERS_IMAGE_UPLOAD_ADMIN_ASSISTANT', 'USERS_IMAGE_UPLOAD_ADMIN_ASSISTANT', _upload_action),

            (_rule70, 'USERS_TECHNICAL_SUPPORT_UPDATE_STATUS', 'USERS_TECHNICAL_SUPPORT_UPDATE_STATUS', _update_action),
            (_rule71, 'USERS_ADMIN_MODULE_UPDATE_STATUS', 'USERS_ADMIN_MODULE_UPDATE_STATUS', _update_action),

            (_rule72, 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_VIEW', 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER', _view_action),
            (_rule73, 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_VIEW', 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH', _view_action),
            (_rule74, 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_VIEW', 'MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE', _view_action),
            (_rule75, 'MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_VIEW',    'MEMBERSHIP_MODULE_MEMBERS_ASSISTANT',    _view_action),

            (_rule76, 'STORE_MODULE_DEPARTMENTS_VIEW',   'STORE_MODULE_DEPARTMENTS', _view_action),
            (_rule77, 'STORE_MODULE_DEPARTMENTS_INSERT', 'STORE_MODULE_DEPARTMENTS', _insert_action),
            (_rule78, 'STORE_MODULE_DEPARTMENTS_UPDATE', 'STORE_MODULE_DEPARTMENTS', _update_action),
            (_rule79, 'STORE_MODULE_DEPARTMENTS_DELETE', 'STORE_MODULE_DEPARTMENTS', _delete_action),

            (_rule80, 'STORE_MODULE_SUBCATEGORIES_VIEW',   'STORE_MODULE_SUBCATEGORIES', _view_action),
            (_rule81, 'STORE_MODULE_SUBCATEGORIES_INSERT', 'STORE_MODULE_SUBCATEGORIES', _insert_action),
            (_rule82, 'STORE_MODULE_SUBCATEGORIES_UPDATE', 'STORE_MODULE_SUBCATEGORIES', _update_action),
            (_rule83, 'STORE_MODULE_SUBCATEGORIES_DELETE', 'STORE_MODULE_SUBCATEGORIES', _delete_action),

            (_rule84, 'STORE_MODULE_PRODUCTS_VIEW',   'STORE_MODULE_PRODUCTS', _view_action),
            (_rule85, 'STORE_MODULE_PRODUCTS_INSERT', 'STORE_MODULE_PRODUCTS', _insert_action),
            (_rule86, 'STORE_MODULE_PRODUCTS_UPDATE', 'STORE_MODULE_PRODUCTS', _update_action),
            (_rule87, 'STORE_MODULE_PRODUCTS_DELETE', 'STORE_MODULE_PRODUCTS', _delete_action),

            (_rule88, 'STORE_MODULE_DEPARTMENTS_STATUS_UPDATE',    'STORE_MODULE_DEPARTMENTS_STATUS', _update_action),
            (_rule89, 'STORE_MODULE_SUBCATEGORIES_STATUS_UPDATE', 'STORE_MODULE_SUBCATEGORIES_STATUS', _update_action),
            (_rule90, 'STORE_MODULE_PRODUCTS_STATUS_UPDATE',      'STORE_MODULE_PRODUCTS_STATUS', _update_action),

            (_rule91, 'UNIQUE_CODE_PREFIXES_VIEW',   'UNIQUE_CODE_PREFIXES', _view_action),
            (_rule92, 'UNIQUE_CODE_PREFIXES_INSERT', 'UNIQUE_CODE_PREFIXES', _insert_action),
            (_rule93, 'UNIQUE_CODE_PREFIXES_UPDATE', 'UNIQUE_CODE_PREFIXES', _update_action),
            (_rule94, 'UNIQUE_CODE_PREFIXES_DELETE', 'UNIQUE_CODE_PREFIXES', _delete_action),

            (_rule95, 'UNIQUE_CODE_GENERATOR', 'UNIQUE_CODE_GENERATOR', _view_action);


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
            (_profile1, _rule39),
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
            (_profile1, _rule76),
            (_profile1, _rule77),
            (_profile1, _rule78),
            (_profile1, _rule79),
            (_profile1, _rule80),
            (_profile1, _rule81),
            (_profile1, _rule82),
            (_profile1, _rule83),
            (_profile1, _rule84),
            (_profile1, _rule85),
            (_profile1, _rule86),
            (_profile1, _rule87),
            (_profile1, _rule88),
            (_profile1, _rule89),
            (_profile1, _rule90),
            (_profile1, _rule91),
            (_profile1, _rule92),
            (_profile1, _rule93),
            (_profile1, _rule94),
            (_profile1, _rule95),

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
            (_profile2, _rule39),
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
            (_profile2, _rule76),
            (_profile2, _rule77),
            (_profile2, _rule78),
            (_profile2, _rule79),
            (_profile2, _rule80),
            (_profile2, _rule81),
            (_profile2, _rule82),
            (_profile2, _rule83),
            (_profile2, _rule84),
            (_profile2, _rule85),
            (_profile2, _rule86),
            (_profile2, _rule87),
            (_profile2, _rule88),
            (_profile2, _rule89),
            (_profile2, _rule90),
            (_profile2, _rule91),
            (_profile2, _rule92),
            (_profile2, _rule93),
            (_profile2, _rule94),
            (_profile2, _rule95),

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
            (_profile3, _rule39),
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
            (_profile3, _rule76),
            (_profile3, _rule77),
            (_profile3, _rule78),
            (_profile3, _rule79),
            (_profile3, _rule80),
            (_profile3, _rule81),
            (_profile3, _rule82),
            (_profile3, _rule83),
            (_profile3, _rule84),
            (_profile3, _rule85),
            (_profile3, _rule86),
            (_profile3, _rule87),
            (_profile3, _rule88),
            (_profile3, _rule89),
            (_profile3, _rule90),
            (_profile3, _rule91),
            (_profile3, _rule92),
            (_profile3, _rule93),
            (_profile3, _rule94),
            (_profile3, _rule95),

            -- ADMIN MODULE
            (_profile4, _rule1),
            (_profile4, _rule15),
            (_profile4, _rule17),
            (_profile4, _rule18),
            (_profile4, _rule29),
            (_profile4, _rule30),
            (_profile4, _rule35),
            (_profile4, _rule36),
            (_profile4, _rule39),
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
            (_profile4, _rule76),
            (_profile4, _rule77),
            (_profile4, _rule78),
            (_profile4, _rule79),
            (_profile4, _rule80),
            (_profile4, _rule81),
            (_profile4, _rule82),
            (_profile4, _rule83),
            (_profile4, _rule84),
            (_profile4, _rule85),
            (_profile4, _rule86),
            (_profile4, _rule87),
            (_profile4, _rule88),
            (_profile4, _rule89),
            (_profile4, _rule90),
            (_profile4, _rule91),
            (_profile4, _rule92),
            (_profile4, _rule93),
            (_profile4, _rule94),
            (_profile4, _rule95),

            -- ASSISTANT
            (_profile5, _rule1),
            (_profile5, _rule16),
            (_profile5, _rule17),
            (_profile5, _rule18),
            (_profile5, _rule31),
            (_profile5, _rule32),
            (_profile5, _rule35),
            (_profile5, _rule36),
            (_profile5, _rule39),
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
            (_profile5, _rule75),
            (_profile5, _rule76),
            (_profile5, _rule77),
            (_profile5, _rule78),
            (_profile5, _rule80),
            (_profile5, _rule81),
            (_profile5, _rule82),
            (_profile5, _rule84),
            (_profile5, _rule85),
            (_profile5, _rule86),
            (_profile5, _rule91),
            (_profile5, _rule92),
            (_profile5, _rule93),
            (_profile5, _rule94),
            (_profile5, _rule95)
            ;

        -- MEMBER
    END $$;
