create table application_category
(
    id int auto_increment,
    name varchar(512) charset utf8mb4 not null,
    constraint application_category_pk
        primary key (id)
)
character set utf8mb4 collate utf8mb4_unicode_ci;
