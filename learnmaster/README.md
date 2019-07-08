# Learn Master Plugin

Learn Master is a plugin support Wordpress site easy to build a education solution.
> Current version : 1.0.15

# Installation
+ Run composer update command to get required components

```text
composer update --prefer-dist -vvv --profile
```
or

```text
php composer.phar update --prefer-dist -vvv --profile
```
+ Building for production environment

```text
composer update --prefer-dist --no-dev --profile
```
# Features
- Shortcode management system
- Layzy shortcodes
- Dynamic post type
- Form builder/Visual composer supported


# Templates
> Override plugin templates

## Email templates
> Supported following templates those allowed theme override via template path

#### instructor_publish_course
> When instructor's course was published

Default template path : 
```text
ABSPATH /wp-contents/plugins/learnmaster/templates/emails/instructor_publish_course.php
```
Override template path : 
```text
ABSPATH /wp-contents/themes/CURRENT_THEME/learnmaster/templates/emails/instructor_publish_course.php
```

Params : 
```test
{INSTRUCTOR_NAME} : Instructor name
{COURSE_LINK} : Course link
{COURSE_TITLE} : Course title
```

#### student_enroll_free
> When student enrolled to a free course

Default template path : 
```text
ABSPATH /wp-contents/plugins/learnmaster/templates/emails/student_enroll_free.php
```
Override template path : 
```text
ABSPATH /wp-contents/themes/CURRENT_THEME/learnmaster/templates/emails/student_enroll_free.php
```

Params : 
```text
{STUDENT_NAME} : Student name
{STUDENT_FIRST_NAME} : Student first name
{STUDENT_PROFILE_URL} : Student profile URL
{COURSE_NAME} : Name of course
{COURSE_URL} : URL of course
```

#### student_enroll_paid
> When student paid and enrolled to course

Default template path : 
```text
ABSPATH /wp-contents/plugins/learnmaster/templates/emails/student_enroll_paid.php
```
Override template path : 
```text
ABSPATH /wp-contents/themes/CURRENT_THEME/learnmaster/templates/emails/student_enroll_paid.php
```

Params : 
```text
{STUDENT_NAME} : Student name
{STUDENT_PROFILE_URL} : Student profile URL
{LIST_ITEMS} : List of course items
{ORDER_TOTAL} : Total amount of order
{ORDER_SUBTOTAL} : Subtotal of order
{ORDER_TAX_VALUE} : Tax of order
{ORDER_TAX_CLASS} : Tax class of order
```

#### student_course_list
> List course in an order

Default template path : 
```text
ABSPATH /wp-contents/plugins/learnmaster/templates/emails/student_course_list.php
```
Override template path : 
```text
ABSPATH /wp-contents/themes/CURRENT_THEME/learnmaster/templates/emails/student_course_list.php
```






# Shortcode List
> List of available shortcodes in Learn Master plugin

## Cart Shortcodes
> Shortcodes related to cart feature

### Shortcode lema_cart
> Show cart page

```text
    [lema_cart]
```


### Shortcode cart-icon
> Show cart icon with number (badget) of items. Also show list of item when mouse hover

```text
    [lema_cart_icon]
```

### Shortcode add-cart
> Show button add to cart

```text
    [lema_add_cart post_id="11550" title="Endroll"]
```
#### attributes:
- post_id (string) : ID of course (post)
- title (string) : title of button

### Shortcode lema_cart
> Show cart content and checkout button

```text
    [lema_cart]
```

### Shortcode lema-checkout
> Show checkout and pagement method

```text
    [lema_checkout order_id="NUMBER"]
```
#### Params : 
- order_id (number) : ID of current order

## Course shortcodes
> Shortcode related to course

### Shortcode lema-course-category
> Use to render Course Category menu.

```text
    [lema_course_category type="box" icon-class="fa fa-user"]
```
#### Attributes
- `type`: `box` or `tabs`. Default: `box`.
- `icon-class`: use css class icon name. Ex: `icon="fa fa-user"` meaning use class `fa fa-user` 
of Font Awesome or `icon="user"` meaning use `fa fa-user`


### Shortcode lema-course
> Use to render Course Card.

```text    
    [lema_course post_id="post_id"][/lema_course]
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

### Shortcode lema-coursecard-title
> Show title of course

```text
    [lema_coursecard_title has_link="0" post_id="123"]
```
- has_link (boolean) : Embed link to course detail   (0/1)
- post_id (string) : ID of course (post)

### Shortcode lema-coursecard-image
> Show course's image

```text
    [lema_coursecard_image post_id=""]
```
#### Supported params :
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

### Shortcode lema-coursecard-category
> Show categories of course

```text
    [lema_coursecard_category post_id="123" limit="4"]
```
- limit (number) : number of categories will be shown
- post_id (string) : ID of course (post)

### Shortcode lema_course_content
> Show categories of course

```text
    [lema_course_content post_id="123" limit="4"]
```
- limit_text (number) : number of text will be shown
- post_id (string) : ID of course (post)

### Shortcode lema-coursecard-description
> Show description of course

```text
    [lema_coursecard_description post_id="123" limit_text="300" afterString="..."]
```
- post_id (string) : ID of course (post)
- limit_text (number) : Limit length of description text
- afterString (string) : show at end of limited description text

### Shortcode lema-coursecard-instructor
> Show instructor of a course

```text
    [lema_coursecard_instructor post_id="123"]
```
- user_by (number): get instructor by value (default '' or id1,id2,id3...)
- user_filter (string) : filter by field ( default 'id' )
- post_id (string) : ID of course (post)
- layout (string) : full/cart ( default : card )
- limit: Limit of course - Default: `-1`
- show_name (boolean) : Show or hide instructor's name (1/0)
- show_image (boolean) : Show or hide instructor's avatar (1/0)
- show_social (boolean) : Show or hide instructor's socials (1/0)
- show_statistic (boolean) : Show or hide instructor's list statistic (1/0)
- show_description(boolean) : Show or hide instructor's description course (1/0)
- show_course_popular (boolean) : Show or hide instructor's list popular course (1/0)
- show_role (boolean) : Show or hide instructor's role(1/0)
- show_icon_avatar (boolean) : Show or hide instructor's avatar icon instructor (1/0)

### Shortcode lema-coursecard-price
> Show price of a course

```text
    [lema_coursecard_price post_id="8217" show_remain_time="true" show_discount_percent="true"]
```
- post_id (string) : ID of course (post)
- show_remain_time (boolean) : If sale price was set, the time of this price campaign will remaining (1/0)
- show_discount_percent (boolean) : If sale price was set, show or hide the text display percentage of discount (1/0)


### Shortcode lema-coursecard-bookmark
> Show bookmark button of the course

```text
    [lema_coursecard_bookmark post_id="8217"]
```
- post_id (numberic) : ID of course


### Shortcode lema-coursecard-bookmark
> Show progress of the course

```text
    [lema_coursecard_progress post_id="8217"]
```
- post_id (numberic) : ID of course



### Shortcode lema-course-list
> This is base course list shortcode. It display all courses by default.

```text
    [lema_course_list]
```
#### attributes:

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
- `show_pagination` (boolean) : Show or hide pagination Defaut: `1` (1/0)
- `show_summary`: Defaut: `0`
- `data`: 

### Shortcode best selling course list
> It display the best selling courses

```text
    [lema_course_list_best_selling]
```
#### attributes:
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
- `limit` : Limit of list - Default: `10`
- `filter_by_cat`: category to filter course

### Shortcode course list by category
> It display the courses by category

```text
    [lema_course_list_category]
```
#### attributes:
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


### Shortcode top-rating course list
> It display the top-rating courses

```text
    [lema_course_list_top_rating]
```
#### attributes:
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


### Shortcode enrolled course list
> It display the courses that the student enrolled

```text
    [lema_course_list_enrolled]
```
#### attributes:
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

```
### Shortcode filter course list
text
    [lema_courselist_filtered]
```
#### attributes: extend [lema_course_list]
- `level_course` : Search by term level course ( id or slug )
- `language_course` : Search by term language course ( id or slug )
- `tag_course` : Search by tag language course ( id or slug )
- `cat_course` : Search by category language course ( id or slug )
- `summary` : Show total courses filtered ( 1/0 , default 0 )
- `sort_type` : Sort type DESC or ASC ( desc/asc )
- `sort_by`   : Sort by attributes course ( default: title )
- `q` : Search by keywords


### Shortcode bookmarked course list
> It display the courses that the student bookmarked or added to favourites

```text
    [lema_course_list_bookmarked]
```
#### attributes:
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

### Shortcode course list of instructor
> It display the courses that are instructed by an instructor

```text
    [lema_course_list_instructed]
```
#### attributes:
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

### Shortcode search-box
> Display search box

```text
    [lema_search_box]
```
 

## Category shortcodes
> Shortcodes related to category 

### Shortcode categorylist
> Show category list applied some filter

```text
    [lema_category_list]
```
#### attributes:
- list_name: render category by list name. Example: list_name="web design, web education". Default empty.
- limit: number category filter
- taxonomy: taxonomy render. Default 'cat_course'
- cols_in_row: default 3
- is_title: default 1
- is_description: default 1
- is_icon: default 1
- limit_description: default 30 words
- filter: feature, top-enroll, ... Default empty

### Shortcode lema_rating
> Show rating of a course

```text
    [lema_rating object_id="postID"]
```
- object_id (string) : ID of course (post)
- review_only (bool) : Show review comment only
- static (bool) : Show rating stars with static param number
- static_value (float) : Number of stars
- label (string) : Label of this rating
- readonly (bool) : No rate action
- style (string) : full or simple



### Shortcode lema_course_custom_attributes

> Show course custom attributes
```php
[lema_course_custom_attributes course_id="COURSE_ID" attributes="COMMA_SEP_ATTR" title="Extra information"]
```

# Change logs
## Version 1.0.7 
    - Fix stupid bugs
## Version 1.0.1
    - Fix some bugs
    - Add more hooks to integrate with other plugin
    - Add supported child plugin Lema Affiliate
## Version 1.0.0
    - Add base object and common structure
    - Symfony components
    - Doctrine cache
    - Setup demo
