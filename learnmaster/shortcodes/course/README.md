# LEARN MASTER

## COURSE SHORTCODE
Use to render Course Card.

```text
    [lema_course post_id="%post_id%"][/lema-course]
```

- `post_id`: is private attribute. 
    
- Shortcode include multi 

## COURSE CATEGORY SHORTCODE
Use to render Course Category menu.

```text
    [lema-course-category type="box" icon-class="fa fa-user"]
```
- `type`: `box` or `tabs`. Default: `box`.
- `icon-class`: use css class icon name. Ex: `icon="fa fa-user"` meaning use class `fa fa-user` of Font Awesome.

# Shortcode course-list
```text
    [lema-course-list]
```
### attributes:
- type: Filter by type ( cat, tag, best-seller,....)
- value: ( value of type: name cat/tag )
- title: ( title course list )
- layout: ( grid / slide : default 'grid' )
- posts_per_page: ( number posts each page : default 10 )
- paging: ( current page to show : defaut 1 )
- orderBy: ( order by name )
- order: ( order type: DESC/ASC - default: DESC )
- show_pagination (boolean) : show paging ( default 1 - show)

# Course Shortcodes

## Course card
> Use to render Course Card 

```text
    [lema_course post_id="%post_id%"][/lema-course]
```
-  post_id: is private attribute
-  Shortcode : include multiple

> Multiple children shortcodes

```text
    [lema_course post_id="POST_ID"]
        [lema_course_header]
            [lema_coursecard_title]
            [lema_coursecard_image]
            [lema_coursecard_category]
        [/lema_course_header]
        [lema_course_content]
            [lema_coursecard_description]
            [lema_coursecard_instructor]
            [lema_coursecard_rating]
        [/lema_course_content]
        [lema_course_footer]
            [lema_coursecard_price]
            [lema_coursecard_bookmark]
        [/lema_course_footer]
    [/lema_course]
```

### Course title
> Show title of course

```text
    [lema_coursecard_title has_link="true" post_id="123"]
```
- has_link (boolean) : Embed link to course detail
- post_id (string) : ID of course (post)

### Course image
> Show course's image

```text
    [lema_coursecard_mage]
```
##### Supported params :
- class
- post_id
- has_link_post
- has_hours
- has_view_button
- text_view_button
- has_add_button
- text_add_button
- has_label
- text_label
- has_note_devices
- text_note_devices
- class_label_block

### Course category
> Show categories of course

```text
    [lema_coursecard_category post_id="123" limit="4"]
```
- limit (number) : number of categories will be shown
- post_id (string) : ID of course (post)

### Course description
> Show description of course

```text
    [lema_coursecard_description post_id="123" limit_text="300" afterString="..."]
```
- post_id (string) : ID of course (post)
- limit_text (number) : Limit length of description text
- afterString (string) : show at end of limited description text

### Course instructor
> Show instructor of a course

```text
    [lema_coursecard_instructor post_id="123"]
```
- user_by (number) : get instructor by value (default '' or id1,id2,id3...)
- user_filter (string) : filter by field ( default 'id' )
- post_id (string) : ID of course (post)
- layout (string) : full/cart ( default : card )

- show_role (boolean) : Show or hide instructor's role (1/0)
- show_name (boolean) : Show or hide instructor's name (1/0)
- show_image (boolean) : Show or hide instructor's avatar (1/0)
- show_social (boolean) : Show or hide instructor's list socials (1/0)
- show_statistic (boolean) : Show or hide instructor's list statistic (1/0)
- show_course_popular (boolean) : Show or hide instructor's list popular course (1/0)
- show_avatar_icon (boolean) : Show or hide instructor's avatar icon instructor (1/0)

### Course rating
> Show rating of a course

```text
    [lema_coursecard_rating]
```
### Course price
> Show price of a course

```text
    [lema_coursecard_price post_id="123" show_remain_time="true" show_discount_percent="true"]
```
- post_id (string) : ID of course (post)
- show_remain_time (boolean) : If sale price was set, the time of this price campaign will remaining
- show_discount_percent (boolean) : If sale price was set, show or hide the text display percentage of discount


### Course bookmark
> Show bookmark button of the course

```
    [lema_coursecard_bookmark post_id="123"]
```
- post_id (numberic) : ID of course


## Course category
> Use to render Course Category menu

```
[lema_course_category type="box" icon="user"]
```

- `type`: `box` or `tabs`- Default: `box`-
- `icon`: use font awesome icon name- Ex: `icon="user"` meaning use `fa fa-user`



## Shortcode course list
> This is base course list shortcode. It display all courses by default.

```
[lema_course_list]
```
### attributes:
- `title`: The caption of the course list
- `layout`: `grid` / `slide` / `list` - Default `grid`
- `posts_per_page`: number posts each page - Default: `10`
- `paging`: current page to show - Defaut: `1`
- `orderBy`: ( order by name )
- `order`: order type: `DESC`/`ASC` - Default: `DESC`
- `cols_on_row`: a number from `1` to `5` - Default: `1`
- `search_term`: term to search courses
- `filter_levels`: levels to filter courses
- `filter_topics`: topics to filter courses
- `filter_languages`: languages to filter courses

## Shortcode best selling course list
> It display the best selling courses

```
[lema_course_list_best_selling]
```
### attributes:
- `title`: The caption of the course list
- `layout`: `grid` or `slide` - Default `grid`
- `posts_per_page`: number posts each page - Default: `10`
- `paging`: current page to show - Defaut: `1`
- `orderBy`: ( order by name )
- `order`: order type: `DESC`/`ASC` - Default: `DESC`
- `cols_on_row`: a number from `1` to `5` - Default: `1`
- `search_term`: term to search courses
- `filter_levels`: levels to filter courses
- `filter_topics`: topics to filter courses
- `filter_languages`: languages to filter courses
- `filter_by_cat`: id or name of category

## Shortcode learning course list
> It display the learning courses

```
[lema_course_list_learning]
```
### attributes:
- `title`: The caption of the course list
- `layout`: `grid` or `slide` - Default `grid`
- `posts_per_page`: number posts each page - Default: `10`
- `paging`: current page to show - Defaut: `1`
- `orderBy`: ( order by name )
- `order`: order type: `DESC`/`ASC` - Default: `DESC`
- `cols_on_row`: a number from `1` to `5` - Default: `1`
- `search_term`: term to search courses
- `filter_levels`: levels to filter courses
- `filter_topics`: topics to filter courses
- `filter_languages`: languages to filter courses

## Shortcode whishlish course list
> It display the whishlish courses

```
[lema_course_list_whishlish]
```
### attributes:
- `title`: The caption of the course list
- `layout`: `grid` or `slide` - Default `grid`
- `posts_per_page`: number posts each page - Default: `10`
- `paging`: current page to show - Defaut: `1`
- `orderBy`: ( order by name )
- `order`: order type: `DESC`/`ASC` - Default: `DESC`
- `cols_on_row`: a number from `1` to `5` - Default: `1`
- `search_term`: term to search courses
- `filter_levels`: levels to filter courses
- `filter_topics`: topics to filter courses
- `filter_languages`: languages to filter courses

## Shortcode course list by category
> It display the courses by category

```
[lema_course_list_category]
```
### attributes:
- `cat`: the category that the courses belong to
- `title`: the caption of the course list
- `layout`: `grid` or `slide` - Default `grid`
- `posts_per_page`: number posts each page - Default: `10`
- `paging`: current page to show - Defaut: `1`
- `orderBy`: ( order by name )
- `order`: order type: `DESC`/`ASC` - Default: `DESC`
- `cols_on_row`: a number from `1` to `5` - Default: `1`
- `search_term`: term to search courses
- `filter_levels`: levels to filter courses
- `filter_topics`: topics to filter courses
- `filter_languages`: languages to filter courses

## Shortcode top-rating course list
> It display the top-rating courses

```
[lema_course_list_top_rating]
```
### attributes:
- `title`: The caption of the course list
- `layout`: `grid` or `slide` - Default `grid`
- `posts_per_page`: number posts each page - Default: `10`
- `paging`: current page to show - Defaut: `1`
- `orderBy`: ( order by name )
- `order`: order type: `DESC`/`ASC` - Default: `DESC`
- `cols_on_row`: a number from `1` to `5` - Default: `1`
- `search_term`: term to search courses
- `filter_levels`: levels to filter courses
- `filter_topics`: topics to filter courses
- `filter_languages`: languages to filter courses

## Shortcode enrolled course list
> It display the courses that the student enrolled

```
[lema_course_list_enrolled]
```
### attributes:
- `student_id`: The user Id of the student
- `title`: The caption of the course list
- `layout`: `grid` or `slide` - Default `grid`
- `posts_per_page`: number posts each page - Default: `10`
- `paging`: current page to show - Defaut: `1`
- `orderBy`: ( order by name )
- `order`: order type: `DESC`/`ASC` - Default: `DESC`
- `cols_on_row`: a number from `1` to `5` - Default: `1`
- `search_term`: term to search courses
- `filter_levels`: levels to filter courses
- `filter_topics`: topics to filter courses
- `filter_languages`: languages to filter courses

## Shortcode bookmarked course list
> It display the courses that the student bookmarked or added to favourites

```
[lema_course_list_bookmarked]
```
### attributes:
- `student_id`: The user Id of the student
- `title`: The caption of the course list
- `layout`: `grid` or `slide` - Default `grid`
- `posts_per_page`: number posts each page - Default: `10`
- `paging`: current page to show - Defaut: `1`
- `orderBy`: ( order by name )
- `order`: order type: `DESC`/`ASC` - Default: `DESC`
- `cols_on_row`: a number from `1` to `5` - Default: `1`
- `search_term`: term to search courses
- `filter_levels`: levels to filter courses
- `filter_topics`: topics to filter courses
- `filter_languages`: languages to filter courses

## Shortcode course list of instructor
> It display the courses that are instructed by an instructor

```
[lema_course_list_instructed]
```
### attributes:
- `instructor_id`: The user Id of the instructor
- `title`: The caption of the course list
- `layout`: `grid` or `slide` - Default `grid`
- `posts_per_page`: number posts each page - Default: `10`
- `paging`: current page to show - Defaut: `1`
- `orderBy`: ( order by name )
- `order`: order type: `DESC`/`ASC` - Default: `DESC`
- `cols_on_row`: a number from `1` to `5` - Default: `1`
- `search_term`: term to search courses
- `filter_levels`: levels to filter courses
- `filter_topics`: topics to filter courses
- `filter_languages`: languages to filter courses

