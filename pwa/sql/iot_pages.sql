create table pages
(
    page_id               int auto_increment
        primary key,
    page_type             varchar(40)            not null,
    page_name             varchar(40)            not null,
    page_uri              varchar(40)            not null,
    page_login_required   int         default 0  not null,
    page_user_member_type varchar(30) default '' null,
    constraint page_uri
        unique (page_uri)
)
    charset = latin1;

INSERT INTO iot.pages (page_id, page_type, page_name, page_uri, page_login_required, page_user_member_type) VALUES (1, 'default', 'home', '/', 0, '');
INSERT INTO iot.pages (page_id, page_type, page_name, page_uri, page_login_required, page_user_member_type) VALUES (2, 'default', 'login', '/login', 0, '');
INSERT INTO iot.pages (page_id, page_type, page_name, page_uri, page_login_required, page_user_member_type) VALUES (3, 'default', 'admin', '/admin', 1, 'admin');
INSERT INTO iot.pages (page_id, page_type, page_name, page_uri, page_login_required, page_user_member_type) VALUES (4, 'default', 'logout', '/logout', 0, '');
INSERT INTO iot.pages (page_id, page_type, page_name, page_uri, page_login_required, page_user_member_type) VALUES (5, 'default', 'setup', '/setup', 0, '');