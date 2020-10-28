alter table triggers
    add recipient varchar(255) null;

INSERT INTO iot.pages (page_type, page_name, page_uri, page_login_required, page_user_member_type) VALUES ('api', 'api_update_triggers', '/api/update/triggers', 0, DEFAULT)
