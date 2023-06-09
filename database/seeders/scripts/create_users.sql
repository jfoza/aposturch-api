-- USER 1
START TRANSACTION;

DO $$

    DECLARE

        _user_uuid   uuid    = uuid_generate_v4();
        _name        varchar = 'Giuseppe Foza';
        _email       varchar = 'gfozza@hotmail.com';
        _password    varchar = general.generate_bcrypt_hash('Teste123');

        _profile varchar := 'ADMIN_MASTER';
        _profile_uuid uuid;

        _module1 uuid;
        _module2 uuid;
        _module3 uuid;
        _module4 uuid;
        _module5 uuid;
        _module6 uuid;
        _module7 uuid;

    BEGIN
        SELECT id INTO _profile_uuid FROM users.profiles WHERE unique_name = _profile;

        SELECT id INTO _module1 FROM module.modules WHERE module_unique_name = 'USERS';
        SELECT id INTO _module2 FROM module.modules WHERE module_unique_name = 'FINANCE';
        SELECT id INTO _module3 FROM module.modules WHERE module_unique_name = 'MEMBERSHIP';
        SELECT id INTO _module4 FROM module.modules WHERE module_unique_name = 'STORE';
        SELECT id INTO _module5 FROM module.modules WHERE module_unique_name = 'GROUPS';
        SELECT id INTO _module6 FROM module.modules WHERE module_unique_name = 'SCHEDULE';
        SELECT id INTO _module7 FROM module.modules WHERE module_unique_name = 'PATRIMONY';

        INSERT INTO users.users (id, name, email, password, active)
        VALUES
            (   _user_uuid,
                _name,
                _email,
                _password,
                true
            );

        INSERT INTO users.admin_users (user_id)
        VALUES
            (
                _user_uuid
            );

        INSERT INTO users.modules_users (user_id, module_id)
        VALUES
            (_user_uuid, _module1),
            (_user_uuid, _module2),
            (_user_uuid, _module3),
            (_user_uuid, _module4),
            (_user_uuid, _module5),
            (_user_uuid, _module6),
            (_user_uuid, _module7);

        INSERT INTO users.profiles_users (profile_id, user_id)
        VALUES
            (
                _profile_uuid,
                _user_uuid
            );
    END $$;
commit;


-- USER 2
START TRANSACTION;

DO $$

    DECLARE

        _user_uuid   uuid    = uuid_generate_v4();
        _name        varchar = 'Otavio Silveira';
        _email       varchar = 'otavio-silveira@hotmail.com';
        _password    varchar = general.generate_bcrypt_hash('Teste123');

        _profile varchar := 'ADMIN_MASTER';
        _profile_uuid uuid;

        _module1 uuid;
        _module2 uuid;
        _module3 uuid;
        _module4 uuid;
        _module5 uuid;
        _module6 uuid;
        _module7 uuid;

    BEGIN
        SELECT id INTO _profile_uuid FROM users.profiles WHERE unique_name = _profile;

        SELECT id INTO _module1 FROM module.modules WHERE module_unique_name = 'USERS';
        SELECT id INTO _module2 FROM module.modules WHERE module_unique_name = 'FINANCE';
        SELECT id INTO _module3 FROM module.modules WHERE module_unique_name = 'MEMBERSHIP';
        SELECT id INTO _module4 FROM module.modules WHERE module_unique_name = 'STORE';
        SELECT id INTO _module5 FROM module.modules WHERE module_unique_name = 'GROUPS';
        SELECT id INTO _module6 FROM module.modules WHERE module_unique_name = 'SCHEDULE';
        SELECT id INTO _module7 FROM module.modules WHERE module_unique_name = 'PATRIMONY';

        INSERT INTO users.users (id, name, email, password, active)
        VALUES
            (   _user_uuid,
                _name,
                _email,
                _password,
                true
            );

        INSERT INTO users.admin_users (user_id)
        VALUES
            (
                _user_uuid
            );

        INSERT INTO users.modules_users (user_id, module_id)
        VALUES
            (_user_uuid, _module1),
            (_user_uuid, _module2),
            (_user_uuid, _module3),
            (_user_uuid, _module4),
            (_user_uuid, _module5),
            (_user_uuid, _module6),
            (_user_uuid, _module7);

        INSERT INTO users.profiles_users (profile_id, user_id)
        VALUES
            (
                _profile_uuid,
                _user_uuid
            );
    END $$;
commit;

-- USER 3
START TRANSACTION;

DO $$

    DECLARE

        _user_uuid   uuid    = uuid_generate_v4();
        _person_uuid uuid    = uuid_generate_v4();
        _member_uuid uuid    = uuid_generate_v4();
        _city_id     uuid;
        _name        varchar = 'Felipe Dutra';
        _email       varchar = 'felipe-dutra@hotmail.com';
        _password    varchar = general.generate_bcrypt_hash('Teste123');
        _phone       varchar = '51912433363';
        _zip_code    varchar = '96010165';
        _address     varchar = 'Rua Tiradentes';
        _district    varchar = 'Centro';
        _number      varchar = '80';
        _complement  varchar = 'casa';
        _city        varchar = 'Pelotas';
        _uf          varchar = 'RS';

        _profile varchar := 'ADMIN_CHURCH';
        _profile_uuid uuid;

        _church_unique_name varchar = 'igreja-biblica-viver-caxias';
        _church_id uuid;

        _module2 uuid;
        _module3 uuid;
        _module4 uuid;
        _module5 uuid;
        _module6 uuid;
        _module7 uuid;

    BEGIN
        SELECT id INTO _profile_uuid FROM users.profiles WHERE unique_name = _profile;
        SELECT id INTO _city_id FROM city.cities WHERE description = _city;
        SELECT id INTO _church_id FROM membership.churches WHERE unique_name = _church_unique_name;

        SELECT id INTO _module2 FROM module.modules WHERE module_unique_name = 'FINANCE';
        SELECT id INTO _module3 FROM module.modules WHERE module_unique_name = 'MEMBERSHIP';
        SELECT id INTO _module4 FROM module.modules WHERE module_unique_name = 'STORE';
        SELECT id INTO _module5 FROM module.modules WHERE module_unique_name = 'GROUPS';
        SELECT id INTO _module6 FROM module.modules WHERE module_unique_name = 'SCHEDULE';
        SELECT id INTO _module7 FROM module.modules WHERE module_unique_name = 'PATRIMONY';

        INSERT INTO person.persons(id, city_id, phone, zip_code, address, number_address, complement, district, uf)
        VALUES(
                  _person_uuid,
                  _city_id,
                  _phone,
                  _zip_code,
                  _address,
                  _number,
                  _complement,
                  _district,
                  _uf
              );

        INSERT INTO users.users (id, person_id, name, email, password, active)
        VALUES
            (   _user_uuid,
                _person_uuid,
                _name,
                _email,
                _password,
                true
            );

        INSERT INTO membership.members(id, user_id)
        VALUES
            (_member_uuid, _user_uuid);

        INSERT INTO users.modules_users (user_id, module_id)
        VALUES
            (_user_uuid, _module2),
            (_user_uuid, _module3),
            (_user_uuid, _module4),
            (_user_uuid, _module5),
            (_user_uuid, _module6),
            (_user_uuid, _module7);

        INSERT INTO users.profiles_users (profile_id, user_id)
        VALUES
            (
                _profile_uuid,
                _user_uuid
            );

        INSERT INTO membership.churches_members (member_id, church_id)
        VALUES
            (
                _member_uuid,
                _church_id
            );
    END $$;
commit;

-- USER 4
START TRANSACTION;

DO $$

    DECLARE

        _user_uuid   uuid    = uuid_generate_v4();
        _person_uuid uuid    = uuid_generate_v4();
        _member_uuid uuid    = uuid_generate_v4();
        _city_id     uuid;
        _name        varchar = 'Fabio Dutra';
        _email       varchar = 'fabio-dutra@hotmail.com';
        _password    varchar = general.generate_bcrypt_hash('Teste123');
        _phone       varchar = '51988443887';
        _zip_code    varchar = '94020220';
        _address     varchar = 'Rua Anastácia Pereira';
        _district    varchar = 'Centro';
        _number      varchar = '80';
        _complement  varchar = 'casa';
        _city        varchar = 'Gravataí';
        _uf          varchar = 'RS';

        _profile varchar := 'ADMIN_MODULE';
        _profile_uuid uuid;

        _church_unique_name varchar = 'igreja-biblica-viver-nh';
        _church_id uuid;

        _module2 uuid;
        _module3 uuid;
        _module4 uuid;
        _module5 uuid;
        _module6 uuid;
        _module7 uuid;

    BEGIN
        SELECT id INTO _profile_uuid FROM users.profiles WHERE unique_name = _profile;
        SELECT id INTO _city_id FROM city.cities WHERE description = _city;
        SELECT id INTO _church_id FROM membership.churches WHERE unique_name = _church_unique_name;

        SELECT id INTO _module2 FROM module.modules WHERE module_unique_name = 'FINANCE';
        SELECT id INTO _module3 FROM module.modules WHERE module_unique_name = 'MEMBERSHIP';
        SELECT id INTO _module4 FROM module.modules WHERE module_unique_name = 'STORE';
        SELECT id INTO _module5 FROM module.modules WHERE module_unique_name = 'GROUPS';
        SELECT id INTO _module6 FROM module.modules WHERE module_unique_name = 'SCHEDULE';
        SELECT id INTO _module7 FROM module.modules WHERE module_unique_name = 'PATRIMONY';

        INSERT INTO person.persons(id, city_id, phone, zip_code, address, number_address, complement, district, uf)
        VALUES(
                  _person_uuid,
                  _city_id,
                  _phone,
                  _zip_code,
                  _address,
                  _number,
                  _complement,
                  _district,
                  _uf
              );

        INSERT INTO users.users (id, person_id, name, email, password, active)
        VALUES
            (   _user_uuid,
                _person_uuid,
                _name,
                _email,
                _password,
                true
            );

        INSERT INTO membership.members(id, user_id)
        VALUES
            (_member_uuid, _user_uuid);


        INSERT INTO users.modules_users (user_id, module_id)
        VALUES
            (_user_uuid, _module2),
            (_user_uuid, _module3),
            (_user_uuid, _module4),
            (_user_uuid, _module5),
            (_user_uuid, _module6),
            (_user_uuid, _module7);

        INSERT INTO users.profiles_users (profile_id, user_id)
        VALUES
            (
                _profile_uuid,
                _user_uuid
            );

        INSERT INTO membership.churches_members (member_id, church_id)
        VALUES
            (
                _member_uuid,
                _church_id
            );
    END $$;
commit;

-- USER 4
START TRANSACTION;

DO $$

    DECLARE

        _user_uuid   uuid    = uuid_generate_v4();
        _person_uuid uuid    = uuid_generate_v4();
        _member_uuid uuid    = uuid_generate_v4();
        _city_id     uuid;
        _name        varchar = 'Usuario Perfil Auxiliar';
        _email       varchar = 'usuario-auxiliar@hotmail.com';
        _password    varchar = general.generate_bcrypt_hash('Teste123');
        _phone       varchar = '51999574813';
        _zip_code    varchar = '95110314';
        _address     varchar = 'Rua Isidoro Dias Lopes';
        _district    varchar = 'Desvio Rizzo';
        _number      varchar = '80';
        _complement  varchar = 'casa';
        _city        varchar = 'Caxias do Sul';
        _uf          varchar = 'RS';

        _profile varchar := 'ASSISTANT';
        _profile_uuid uuid;

        _church_unique_name varchar = 'igreja-teste-1';
        _church_id uuid;

        _module2 uuid;
        _module3 uuid;
        _module4 uuid;
        _module5 uuid;
        _module6 uuid;
        _module7 uuid;

    BEGIN
        SELECT id INTO _profile_uuid FROM users.profiles WHERE unique_name = _profile;
        SELECT id INTO _city_id FROM city.cities WHERE description = _city;
        SELECT id INTO _church_id FROM membership.churches WHERE unique_name = _church_unique_name;

        SELECT id INTO _module2 FROM module.modules WHERE module_unique_name = 'FINANCE';
        SELECT id INTO _module3 FROM module.modules WHERE module_unique_name = 'MEMBERSHIP';
        SELECT id INTO _module4 FROM module.modules WHERE module_unique_name = 'STORE';
        SELECT id INTO _module5 FROM module.modules WHERE module_unique_name = 'GROUPS';
        SELECT id INTO _module6 FROM module.modules WHERE module_unique_name = 'SCHEDULE';
        SELECT id INTO _module7 FROM module.modules WHERE module_unique_name = 'PATRIMONY';

        INSERT INTO person.persons(id, city_id, phone, zip_code, address, number_address, complement, district, uf)
        VALUES(
                  _person_uuid,
                  _city_id,
                  _phone,
                  _zip_code,
                  _address,
                  _number,
                  _complement,
                  _district,
                  _uf
              );

        INSERT INTO users.users (id, person_id, name, email, password, active)
        VALUES
            (   _user_uuid,
                _person_uuid,
                _name,
                _email,
                _password,
                true
            );

        INSERT INTO membership.members(id, user_id)
        VALUES
            (_member_uuid, _user_uuid);

        INSERT INTO users.modules_users (user_id, module_id)
        VALUES
            (_user_uuid, _module2),
            (_user_uuid, _module3),
            (_user_uuid, _module4),
            (_user_uuid, _module5),
            (_user_uuid, _module6),
            (_user_uuid, _module7);

        INSERT INTO users.profiles_users (profile_id, user_id)
        VALUES
            (
                _profile_uuid,
                _user_uuid
            );

        INSERT INTO membership.churches_members (member_id, church_id)
        VALUES
            (
                _member_uuid,
                _church_id
            );
    END $$;
commit;

-- INACTIVE USER
START TRANSACTION;

DO $$

    DECLARE

        _user_uuid   uuid    = uuid_generate_v4();
        _person_uuid uuid    = uuid_generate_v4();
        _city_id     uuid;
        _name        varchar = 'Inactive User';
        _email       varchar = 'inactive-user@hotmail.com';
        _password    varchar = general.generate_bcrypt_hash('Teste123');
        _phone       varchar = '51999999999';
        _zip_code    varchar = '99999999';
        _address     varchar = 'Rua Otto Daudt';
        _district    varchar = 'Feitoria';
        _number      varchar = '1770';
        _complement  varchar = 'casa';
        _city        varchar = 'São Leopoldo';
        _uf          varchar = 'RS';

        _profile varchar := 'ADMIN_MASTER';
        _profile_uuid uuid;

        _module1 uuid;
        _module2 uuid;
        _module3 uuid;
        _module4 uuid;
        _module5 uuid;
        _module6 uuid;
        _module7 uuid;

    BEGIN
        SELECT id INTO _profile_uuid FROM users.profiles WHERE unique_name = _profile;
        SELECT id INTO _city_id FROM city.cities WHERE description = _city;

        SELECT id INTO _module1 FROM module.modules WHERE module_unique_name = 'USERS';
        SELECT id INTO _module2 FROM module.modules WHERE module_unique_name = 'FINANCE';
        SELECT id INTO _module3 FROM module.modules WHERE module_unique_name = 'MEMBERSHIP';
        SELECT id INTO _module4 FROM module.modules WHERE module_unique_name = 'STORE';
        SELECT id INTO _module5 FROM module.modules WHERE module_unique_name = 'GROUPS';
        SELECT id INTO _module6 FROM module.modules WHERE module_unique_name = 'SCHEDULE';
        SELECT id INTO _module7 FROM module.modules WHERE module_unique_name = 'PATRIMONY';

        INSERT INTO person.persons(id, city_id, phone, zip_code, address, number_address, complement, district, uf)
        VALUES(
                  _person_uuid,
                  _city_id,
                  _phone,
                  _zip_code,
                  _address,
                  _number,
                  _complement,
                  _district,
                  _uf
              );

        INSERT INTO users.users (id, person_id, name, email, password, active)
        VALUES
            (   _user_uuid,
                _person_uuid,
                _name,
                _email,
                _password,
                false
            );

        INSERT INTO users.admin_users (user_id)
        VALUES
            (
                _user_uuid
            );

        INSERT INTO users.modules_users (user_id, module_id)
        VALUES
            (_user_uuid, _module1),
            (_user_uuid, _module2),
            (_user_uuid, _module3),
            (_user_uuid, _module4),
            (_user_uuid, _module5),
            (_user_uuid, _module6),
            (_user_uuid, _module7);

        INSERT INTO users.profiles_users (profile_id, user_id)
        VALUES
            (
                _profile_uuid,
                _user_uuid
            );
    END $$;
commit;

-- USER 6
START TRANSACTION;

DO $$

    DECLARE

        _user_uuid   uuid    = uuid_generate_v4();
        _person_uuid uuid    = uuid_generate_v4();
        _member_uuid uuid    = uuid_generate_v4();
        _city_id     uuid;
        _name        varchar = 'Usuario Auxiliar Caxias';
        _email       varchar = 'usuario-auxiliar-caxias@hotmail.com';
        _password    varchar = general.generate_bcrypt_hash('Teste123');
        _phone       varchar = '51999574813';
        _zip_code    varchar = '95110314';
        _address     varchar = 'Rua Isidoro Dias Lopes';
        _district    varchar = 'Desvio Rizzo';
        _number      varchar = '80';
        _complement  varchar = 'casa';
        _city        varchar = 'Caxias do Sul';
        _uf          varchar = 'RS';

        _profile varchar := 'ASSISTANT';
        _profile_uuid uuid;

        _church_unique_name varchar = 'igreja-biblica-viver-caxias';
        _church_id uuid;

        _module2 uuid;
        _module3 uuid;
        _module4 uuid;
        _module5 uuid;
        _module6 uuid;
        _module7 uuid;

    BEGIN
        SELECT id INTO _profile_uuid FROM users.profiles WHERE unique_name = _profile;
        SELECT id INTO _city_id FROM city.cities WHERE description = _city;
        SELECT id INTO _church_id FROM membership.churches WHERE unique_name = _church_unique_name;

        SELECT id INTO _module2 FROM module.modules WHERE module_unique_name = 'FINANCE';
        SELECT id INTO _module3 FROM module.modules WHERE module_unique_name = 'MEMBERSHIP';
        SELECT id INTO _module4 FROM module.modules WHERE module_unique_name = 'STORE';
        SELECT id INTO _module5 FROM module.modules WHERE module_unique_name = 'GROUPS';
        SELECT id INTO _module6 FROM module.modules WHERE module_unique_name = 'SCHEDULE';
        SELECT id INTO _module7 FROM module.modules WHERE module_unique_name = 'PATRIMONY';

        INSERT INTO person.persons(id, city_id, phone, zip_code, address, number_address, complement, district, uf)
        VALUES(
                  _person_uuid,
                  _city_id,
                  _phone,
                  _zip_code,
                  _address,
                  _number,
                  _complement,
                  _district,
                  _uf
              );

        INSERT INTO users.users (id, person_id, name, email, password, active)
        VALUES
            (   _user_uuid,
                _person_uuid,
                _name,
                _email,
                _password,
                true
            );

        INSERT INTO membership.members(id, user_id)
        VALUES
            (_member_uuid, _user_uuid);

        INSERT INTO users.modules_users (user_id, module_id)
        VALUES
            (_user_uuid, _module2),
            (_user_uuid, _module3),
            (_user_uuid, _module4),
            (_user_uuid, _module5),
            (_user_uuid, _module6),
            (_user_uuid, _module7);

        INSERT INTO users.profiles_users (profile_id, user_id)
        VALUES
            (
                _profile_uuid,
                _user_uuid
            );

        INSERT INTO membership.churches_members (member_id, church_id)
        VALUES
            (
                _member_uuid,
                _church_id
            );
    END $$;
commit;


-- USER 7
START TRANSACTION;

DO $$

    DECLARE

        _user_uuid   uuid    = uuid_generate_v4();
        _person_uuid uuid    = uuid_generate_v4();
        _member_uuid uuid    = uuid_generate_v4();
        _city_id     uuid;
        _name        varchar = 'Usuario para testes';
        _email       varchar = 'usuario-para-testes@hotmail.com';
        _password    varchar = general.generate_bcrypt_hash('Teste123');
        _phone       varchar = '51999574813';
        _zip_code    varchar = '95110314';
        _address     varchar = 'Rua Isidoro Dias Lopes';
        _district    varchar = 'Desvio Rizzo';
        _number      varchar = '80';
        _complement  varchar = 'casa';
        _city        varchar = 'Caxias do Sul';
        _uf          varchar = 'RS';

        _profile varchar := 'ASSISTANT';
        _profile_uuid uuid;

        _church_unique_name varchar = 'igreja-biblica-viver-caxias';
        _church_id uuid;

        _module2 uuid;
        _module3 uuid;
        _module4 uuid;
        _module5 uuid;
        _module6 uuid;
        _module7 uuid;

    BEGIN
        SELECT id INTO _profile_uuid FROM users.profiles WHERE unique_name = _profile;
        SELECT id INTO _city_id FROM city.cities WHERE description = _city;
        SELECT id INTO _church_id FROM membership.churches WHERE unique_name = _church_unique_name;

        SELECT id INTO _module2 FROM module.modules WHERE module_unique_name = 'FINANCE';
        SELECT id INTO _module3 FROM module.modules WHERE module_unique_name = 'MEMBERSHIP';
        SELECT id INTO _module4 FROM module.modules WHERE module_unique_name = 'STORE';
        SELECT id INTO _module5 FROM module.modules WHERE module_unique_name = 'GROUPS';
        SELECT id INTO _module6 FROM module.modules WHERE module_unique_name = 'SCHEDULE';
        SELECT id INTO _module7 FROM module.modules WHERE module_unique_name = 'PATRIMONY';

        INSERT INTO person.persons(id, city_id, phone, zip_code, address, number_address, complement, district, uf)
        VALUES(
                  _person_uuid,
                  _city_id,
                  _phone,
                  _zip_code,
                  _address,
                  _number,
                  _complement,
                  _district,
                  _uf
              );

        INSERT INTO users.users (id, person_id, name, email, password, active)
        VALUES
            (   _user_uuid,
                _person_uuid,
                _name,
                _email,
                _password,
                true
            );

        INSERT INTO membership.members(id, user_id)
        VALUES
            (_member_uuid, _user_uuid);

        INSERT INTO users.modules_users (user_id, module_id)
        VALUES
            (_user_uuid, _module2),
            (_user_uuid, _module3),
            (_user_uuid, _module4),
            (_user_uuid, _module5),
            (_user_uuid, _module6),
            (_user_uuid, _module7);

        INSERT INTO users.profiles_users (profile_id, user_id)
        VALUES
            (
                _profile_uuid,
                _user_uuid
            );

        INSERT INTO membership.churches_members (member_id, church_id)
        VALUES
            (
                _member_uuid,
                _church_id
            );
    END $$;
commit;
