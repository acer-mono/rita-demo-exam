create table application
(
    id bigint auto_increment,
    author_id bigint not null,
    resolver_id bigint null,
    category_id int not null,
    title varchar(512) charset utf8mb4 not null,
    description text charset utf8mb4 not null,
    photo_before text charset utf8mb4 not null,
    photo_after text charset utf8mb4 null,
    status int not null default 0,
    rejection_reason text null,
    created_at datetime not null,
    updated_at datetime not null,
    constraint application_pk
        primary key (id),
    constraint application_author_fk
        foreign key (author_id) references user (id),
    constraint application_category_fk
        foreign key (category_id) references application_category (id),
    constraint application_resolver_fk
        foreign key (resolver_id) references user (id)
)
character set utf8mb4 collate utf8mb4_unicode_ci;
