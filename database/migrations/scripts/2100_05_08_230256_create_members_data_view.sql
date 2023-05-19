CREATE OR REPLACE VIEW membership.get_members_data_view AS
SELECT
    mm.id AS member_id,
    mm.code AS member_code,
    uu.id AS user_id,
    up.id AS profile_id,
    up.description AS profile_description,
    up.unique_name AS profile_unique_name,
    uu.name,
    uu.email,
    pp.id AS person_id,
    pp.phone,
    pp.address,
    pp.number_address,
    pp.complement,
    pp.district,
    pp.zip_code,
    pp.city_id AS user_city_id,
    cc.description AS user_city_description,
    cc.uf,
    uu.active AS user_active,
    uu.created_at AS user_created_at,
    COALESCE((
        SELECT json_agg(
            json_build_object(
               'church_id', smc.id,
               'church_name', smc.name,
               'church_unique_name', smc.unique_name,
               'church_phone', smc.phone,
               'church_email', smc.email,
               'church_active', smc.active
            )
        )
        FROM membership.churches AS smc
                 JOIN membership.churches_members AS smcm ON smc.id = smcm.church_id
                 JOIN membership.members AS smm ON smcm.member_id = smm.id
        WHERE smm.id = mm.id
        GROUP BY smc.id
    ), '[]'::json) AS churches

FROM membership.members AS mm

JOIN users.users AS uu ON mm.user_id = uu.id
JOIN users.profiles_users AS upu ON uu.id = upu.user_id
JOIN users.profiles AS up ON upu.profile_id = up.id
JOIN person.persons AS pp ON uu.person_id = pp.id
LEFT JOIN city.cities AS cc ON pp.city_id = cc.id;
