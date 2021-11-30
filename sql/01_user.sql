create table user
(
    id bigint auto_increment,
    login varchar(255) not null,
    email varchar(255) not null,
    password varchar(255) not null,
    name varchar(255) not null,
    roles text not null,
    constraint user_pk
        primary key (id)
)
character set utf8mb4 collate utf8mb4_unicode_ci;

create unique index user_login_uindex
    on user (login);
