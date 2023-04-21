START TRANSACTION;

DO $$

    DECLARE
        _id uuid                = uuid_generate_v4();
        _name varchar           = 'Igreja Bíblica Viver NH';
        _unique_name varchar    = 'igreja-biblica-viver-nh';
        _phone varchar          = '51999999999';
        _email varchar          = 'ibvnh@gmail.com';
        _youtube varchar        = 'https://www.youtube.com/channel/UCUjfOsd_ZJJb36JbQ9H1sBA';
        _facebook varchar       = 'https://www.facebook.com/igrejabiblicaviver/';
        _instagram varchar      = 'https://www.instagram.com/igrejaviver/';
        _zip_code varchar       = '93320012';
        _address varchar        = 'Av. Nações Unidas';
        _number_address varchar = '2815';
        _complement varchar     = '';
        _district varchar       = 'Rio Branco';
        _uf varchar             = 'RS';
        _city_id uuid;
        _active boolean         = true;

        _city_description varchar = 'Novo Hamburgo';

    BEGIN
        SELECT id INTO _city_id FROM city.cities WHERE description = _city_description;

        INSERT INTO members.churches
        (
            id,
            name,
            unique_name,
            phone,
            email,
            youtube,
            facebook,
            instagram,
            zip_code,
            address,
            number_address,
            complement,
            district,
            uf,
            city_id,
            active
        )
        VALUES
            (
                _id,
                _name,
                _unique_name,
                _phone,
                _email,
                _youtube,
                _facebook,
                _instagram,
                _zip_code,
                _address,
                _number_address,
                _complement,
                _district,
                _uf,
                _city_id,
                _active
            );
    END $$;
commit;

START TRANSACTION;

DO $$

    DECLARE
        _id uuid                = uuid_generate_v4();
        _name varchar           = 'Igreja Bíblica Viver Caxias';
        _unique_name varchar    = 'igreja-biblica-viver-caxias';
        _phone varchar          = '51999999999';
        _email varchar          = 'ibvcx@gmail.com';
        _youtube varchar        = 'https://www.youtube.com/channel/UCUjfOsd_ZJJb36JbQ9H1sBA';
        _facebook varchar       = 'https://www.facebook.com/igrejabiblicaviver/';
        _instagram varchar      = 'https://www.instagram.com/igrejaviver/';
        _zip_code varchar       = '95096830';
        _address varchar        = 'Rua Alexandre Peretti';
        _number_address varchar = '2815';
        _complement varchar     = '';
        _district varchar       = 'Charqueadas';
        _uf varchar             = 'RS';
        _city_id uuid;
        _active boolean         = true;

        _city_description varchar = 'Caxias do Sul';

    BEGIN
        SELECT id INTO _city_id FROM city.cities WHERE description = _city_description;

        INSERT INTO members.churches
        (
            id,
            name,
            unique_name,
            phone,
            email,
            youtube,
            facebook,
            instagram,
            zip_code,
            address,
            number_address,
            complement,
            district,
            uf,
            city_id,
            active
        )
        VALUES
            (
                _id,
                _name,
                _unique_name,
                _phone,
                _email,
                _youtube,
                _facebook,
                _instagram,
                _zip_code,
                _address,
                _number_address,
                _complement,
                _district,
                _uf,
                _city_id,
                _active
            );
    END $$;
commit;

DO $$

    DECLARE
        _id uuid                = uuid_generate_v4();
        _name varchar           = 'Igreja Teste 1';
        _unique_name varchar    = 'igreja-teste-1';
        _phone varchar          = '51999999999';
        _email varchar          = 'ibvcx@gmail.com';
        _youtube varchar        = 'https://www.youtube.com/channel/UCUjfOsd_ZJJb36JbQ9H1sBA';
        _facebook varchar       = 'https://www.facebook.com/igrejabiblicaviver/';
        _instagram varchar      = 'https://www.instagram.com/igrejaviver/';
        _zip_code varchar       = '99701534';
        _address varchar        = 'Rua José Ferrari';
        _number_address varchar = '2815';
        _complement varchar     = '';
        _district varchar       = 'José Bonifácio';
        _uf varchar             = 'RS';
        _city_id uuid;
        _active boolean         = true;

        _city_description varchar = 'Erechim';

    BEGIN
        SELECT id INTO _city_id FROM city.cities WHERE description = _city_description;

        INSERT INTO members.churches
        (
            id,
            name,
            unique_name,
            phone,
            email,
            youtube,
            facebook,
            instagram,
            zip_code,
            address,
            number_address,
            complement,
            district,
            uf,
            city_id,
            active
        )
        VALUES
            (
                _id,
                _name,
                _unique_name,
                _phone,
                _email,
                _youtube,
                _facebook,
                _instagram,
                _zip_code,
                _address,
                _number_address,
                _complement,
                _district,
                _uf,
                _city_id,
                _active
            );
    END $$;
commit;

DO $$

    DECLARE
        _id uuid                = uuid_generate_v4();
        _name varchar           = 'Igreja Teste 2';
        _unique_name varchar    = 'igreja-teste-2';
        _phone varchar          = '51999999999';
        _email varchar          = 'ibvcx@gmail.com';
        _youtube varchar        = 'https://www.youtube.com/channel/UCUjfOsd_ZJJb36JbQ9H1sBA';
        _facebook varchar       = 'https://www.facebook.com/igrejabiblicaviver/';
        _instagram varchar      = 'https://www.instagram.com/igrejaviver/';
        _zip_code varchar       = '96412852';
        _address varchar        = 'Travessa Luiz Carlos Pereira';
        _number_address varchar = '2815';
        _complement varchar     = '';
        _district varchar       = 'Getúlio Vargas';
        _uf varchar             = 'RS';
        _city_id uuid;
        _active boolean         = true;

        _city_description varchar = 'Bagé';

    BEGIN
        SELECT id INTO _city_id FROM city.cities WHERE description = _city_description;

        INSERT INTO members.churches
        (
            id,
            name,
            unique_name,
            phone,
            email,
            youtube,
            facebook,
            instagram,
            zip_code,
            address,
            number_address,
            complement,
            district,
            uf,
            city_id,
            active
        )
        VALUES
            (
                _id,
                _name,
                _unique_name,
                _phone,
                _email,
                _youtube,
                _facebook,
                _instagram,
                _zip_code,
                _address,
                _number_address,
                _complement,
                _district,
                _uf,
                _city_id,
                _active
            );
    END $$;
commit;
