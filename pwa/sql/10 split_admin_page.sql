UPDATE iot.pages t SET t.page_name = 'link_modules', t.page_uri = '/link' WHERE t.page_name = 'admin';

INSERT INTO iot.pages (page_type, page_name, page_uri, page_login_required, page_user_member_type) VALUES ('default', 'set_triggers', '/triggers', 1, 'admin');

