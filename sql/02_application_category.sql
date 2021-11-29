create table application_category
(
    id int auto_increment,
    uid varchar(512) not null,
    name varchar(512) not null,
    constraint application_category_pk
        primary key (id)
);

create unique index application_category_uid_uindex
    on application_category (uid);