Create a CMS using PHP OOPS and MySQL
# User
- Sign up/ Login page
- Users can View Spaces, create Pages and sub-pages under that space
- Create page - page title, add images, videos, tables, checkbox, text size and text color
- Once completed the content, Publish button to publish the page
- create subpages under pages with the same features
- Option to give comment for a page
- View all the other pages present in the space

# Admin
- To give permission for space access
- To create spaces
- View all the spaces, pages and sub-pages
- View of all the users and the access for each space

# Project structure
# User view:

- LoginPage.php: Login page for exisiting users
- userPage.php: Lists all spaces
- user_space_details.php: Lists all pages and subpages under the pages and If clicked on pages every content of it will get displayed, pages will contain comments under it
- page_form.php: Create page button is clicked, it moves to this page and textarea to collect data from user and store in pages table
- subpage_form.php: when the '+' button is clicked, it moves to this page and textarea to collect data from user and store in subpages table

# Admin view:
- LoginPage.php: Login page for exisiting users
- AdminLogin.php: credetials for admin
- Admin.php: To create new spaces and lists spaces
- Admin_space_details.php: Lists all pages and subpages list and give access for users

# Database tables:
- users: id, username, password, new-password, space_access
- spaces: spaceId, spaceName, description
- pages: page_id, content, spaceId, title
- subpages: sub_id, content, page_id, title
- comments: cmt_id, page_id, usr_cmt, cmt
