# CHANGELOG

## v3.0.1
* [NEW]    add verify csrf token middleware (@Hank)
* [NEW]    add js, css file checksum command (@Hank) 
* [CHANGE] upgrade laravel 5.2 to 5.3 issue #53
* [FIXED]  fix access token miss issue issue #54

## v3.0.0
* [NEW]    new OAuth login (@Hank)
* [NEW]    login limit issue #48
* [NEW]    new duplicate login check (@Hank)
* [NEW]    create redis server (@Hank)
* [CHANGE] upgrade laravel 5.1 to 5.2 (@Hank)
* [CHANGE] revise action log issue #45
* [CHANGE] refactor curl to guzzle client (@Hank)
* [CHANGE] revise login page (@Hank)
* [CHANGE] integrate solution list API (@Hank)
* [CHANGE] integrate solution detail API (@Hank)
* [CHANGE] integrate solution edit API (@Hank)
* [CHANGE] integrate solution change type API (@Hank)
* [CHANGE] integrate on shelf solution API (@Hank)
* [CHANGE] revise export CSV function (@Hank)
* [CHANGE] remove duplicate solution related code (@Hank)
* [FIXED]  fix user registration report error (@Hank)
* [FIXED]  fix project referral data error issue #47
* [FIXED]  fix owner info error of solution edit page issue #38

## v2.6.6
* [FIXED] fix uppercase/lowercase error of project uri file (@Hank) 

## v2.6.5
* [CHANGE] integrate release project schedule API (@Hank)
* [CHANGE] integrate approve solution API (@Hank)
* [CHANGE] integrate approve expert API (@Hank)

## v2.6.4
* [FIXED] fix AIT tour form datetime parse error (@Hank)

## v2.6.3
* [FIXED] fix project report match search error (@Hank)
* [FIXED] fix owner user type error of solution update page (@Hank)

## v2.6.2
* [CHANGE] improve performance (@Hank)
* [CHANGE] remove inbox model (@Hank)

## v2.6.1
* [CHANGE] adjust AIT event email send status (@Hank)

## v2.6.0
* [NEW] add AIT tour form report (@Hank)
* [CHANGE] adjust editor role access privileges (@Hank)
* [FIXED] fix landing expert operate issue (@Hank)

## v2.5.5
* [FIXED] fix project's grade display of backend, frontend pm (@Hank)

## v2.5.4
* [NEW] add action search of member and project (@Hank)
* [NEW] add schedule email not sent data of project (@Hank)
* [CHANGE] change column order of member and project (@Hank)
* [UPDATE] update project category (@Hank)

## v2.5.3
* [FIXED] fix member,project,solution paginate error (#35)
* [FIXED] fix registration report data error (@Hank)
* [FIXED] fix user type filter error (@Hank)

## v2.5.2
* [FIXED] fix solution update fail (@Hank)

## v2.5.1
* [FIXED] remove email template code (@Hank)

## v2.5.0
* [NEW] avoid cache js & css (@Hank)
* [NEW] new AIT q4 report (@Hank)
* [NEW] new float thead (@Hank)
* [REMOVE] remove email template (@Hank)

## v2.4.5
* [FIXED] Fix project assigned PM search error (#33)

## v2.4.4
* [FIXED] Fix landing expert unable to update expert list (#32)

## v2.4.3
* [FIXED] Fix project update occur strengths column data format error (@Hank)
* [FIXED] Fix project update occur tag and resource column data format error (@Hank)
* [FIXED] Fix project detail company name link error (@Hank)
* [FIXED] Fix solution no have image update error (@Hank) 

## v2.4.2
* [CHANGE] Revise manager head can change PM and Premium expert user type (@Hank)

## v2.4.1
* [CHANGE] Change favicon.ico (@Hank)
* [FIXED]  Fix editor role browse member detail view error (@Hank)

## v2.4.0 
* [NEW]    New user_type premium-expert (@Hank) 
* [NEW]    New user internal memo function (@Hank)
* [NEW]    New hwtrek service api interface (@Hank)
* [NEW]    New user suspend function (@Hank)
* [CHANGE] Use string enum as user_type (@Keven)
* [CHANGE] modify project & member update behavior (@Hank) 
* [FIXED]  fix solution list data error (@Hank)

## v2.3.1
* [CHANGE] modify solution search (@Hank)
* [CHANGE] modify member search (@Hank)
* [CHANGE] modify project report wording & add user referrals total number (@Hank)
* [FIXED]  fix backend team creator user not has hwtrek member column (@Hank)

## v2.3.0
* [CHANGE] optimization project report & project (@Hank)
* [CHANGE] adjust proposed & referral dialog display (@Hank)
* [CHANGE] adjust session life time 6 hours (@Hank)
* [FIXED]  fix project referrals data error (@Hank)
* [FIXED]  fix editor style same forntend (@Hank)
* [FIXED]  fix member list & solution list search bug (@Hank)
* [NEW]    recommend expert send mail add collaborate pending list (@Hank)

## v2.2.0 
* [CHANGE] revise project function (@Hank)
* [CHANGE] revise project search function (@Hank) 
* [CHANGE] revise date time format (Apr 12, 2016) (@Hank)
* [CHANGE] revise project tech tag system (@Hank) 
* [NEW] add referrals & propose solution statistics (@Hank) 
* [NEW] add project report function (@Hank)


## v2.1.1
* [FIXED] fix tour form user project id null issue (@Hank)

## v2.1.0
* [NEW] add event questionnaire summary (@Hank)
* [CHANGE] adjust member view authorization of editor role (@Hank)
* [FIXED] fix event report note bug & select user issue (@Hank)

## v2.0.5
* [FIXED] fix event report count error (@Hank)
* [NEW] add event report unique count information (@Hank)

## v2.0.4
* [FIXED] fix event report incomplete data error (@Hank)

## v2.0.3
* [FIXED] fix event report location error (@Hank)
* [CHANGE] adjust word count display (@Hank)

## v2.0.2
* [FIXED] fix event report note function (@Hank)

## v2.0.1
* [FIXED] fix solution limited (@Hank)

## v2.0.0
* [NEW] add events report (@Hank)
* [FIXED] fix apply to be expert message empty issue (@Hank)

## v1.7.0
* [NEW] add user attachment function in member (@Hank)
* [FIXED] fix editor upload image error (@Hank)

## v1.6.0
* [NEW] creator upgrade expert (@Hank)
* [CHANGE] adjust comment report (@Hank)

## v1.5.9
* [FIXED] fix hub questionnaires recommend expert bug (@Hank)

## v1.5.8
* [FIXED] fix JSON.parse parse quote error from solution edit image (@Hank)
* [FIXED] fix solution edit, array not found thumb_delete_x index key error (@Hank)

## v1.5.7
* [FIXED] fix purifier clean video not show (@Hank)
* [FIXED] fix .svg .bmp image upload error  (@Hank)

## v1.5.6
* [FIXED] fix user update error (@Hank)

## v1.5.5
* [FIXED] fix user update error (@Sylvia)

## v1.5.4
* [NEW] new role editor (@Sylvia)
* [NEW] add operate log to log server (@Sylvia)
* [NEW] add project delete function (@Sylvia)

## v1.5.3
* [CHANGE] comment report display (@Keven)
* [FIXED] change view of comment report (#16)
* [FIXED] add sender count to comment report (#17)
* [FIXED] schedule note function (#18)
* [FIXED] avoid edit user submit email index lost issue (@Hank)
