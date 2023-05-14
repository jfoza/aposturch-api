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
    mc.id AS church_id,
    mc.name AS church_name,
    mc.unique_name AS church_unique_name

FROM membership.members AS mm

JOIN membership.churches_members AS mcm ON mm.id = mcm.member_id
JOIN membership.churches AS mc ON mcm.church_id = mc.id
JOIN users.users AS uu ON mm.user_id = uu.id
JOIN users.profiles_users AS upu ON uu.id = upu.user_id
JOIN users.profiles AS up ON upu.profile_id = up.id
JOIN person.persons AS pp ON uu.person_id = pp.id
LEFT JOIN city.cities AS cc ON pp.city_id = cc.id;
