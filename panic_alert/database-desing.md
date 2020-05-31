### Database Design
- users
	- id
	- username
	- key
	- nicename
	- email
	- phone
	- bvn
	- address
	- kins_nicename
	- kins_phone	
	- kins_address
	- kins_type
	- art
	- time
- panics
	- id
	- user
	- category
	- title
	- content
	- arts
	- state
	- time
- categories
	- id
	- type
	- title
	- description
	- time
- report
	- id
	- panic
	- reporter
	- time
- session
	- id
	- user
	- init
	- state