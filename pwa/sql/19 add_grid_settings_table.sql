create table grid_settings (
    id int auto_increment,
    user_id int not null,
    radius int null,
    snapToGrid int null,
    mapName varchar(60) default 'Map name',
    created_at datetime not null default current_timestamp(),
    updated_at datetime not null default current_timestamp(),

    constraint grid_settings_pk 
        primary key (id),
    constraint grid_settings_users_id_fk 
        foreign key (user_id) references users (user_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);