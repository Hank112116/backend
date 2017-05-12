# CHANGELOG

## v3.3.1 (2017-05-12)
* [FIXED]  fix member matching report error (issue #77)

## v3.3.0 (2017-05-11)
* [NEW]    add AIT 2017 Q2 questionnaire report (issue #72)
* [NEW]    add user's company logo upload function  (issue #60, #74)
* [CHANGE] integrate low priority object API
* [FIXED]  fix google map API warning
* [FIXED]  fix member matching report collaborators miss creator data (#75)
* [FIXED]  fix feature object link miss error (#73)

## v3.2.0 (2017-04-07)
* [NEW]   add AIT 2017 Q2 report
* [FIXED] fix user name edit error (issue #69)
* [FIXED] fix solution edit page not display (issue #70)

## v3.1.4 (2017-03-17)
* [CHANGE] integrate feature API (#61)
* [CHANGE] revise feature duplicate issue (issue #67)
* [FIXED]  fix feature list not display bug (issue #68)

## v3.1.3 (2017-03-10)
* [CHANGE] revise feature UI (#66)
* [REMOVE] remove home page expert list (@Hank)

## v3.1.2 (2017-03-03)
* [NEW]    add premium-creator role (issue #59)
* [NEW]    add Low Priority List (issue #63)
* [CHANGE] upgrade laravel 5.3 to 5.4 (issue #62)

## v3.1.1 (2017-02-16)
* [CHANGE] revise  member matching report display style (@Hank)

## v3.1.0 (2017-02-15)
* [NEW]    add member matching report (issue #57)
* [CHANGE] revise update user inactive behavior (issue #58)
* [CHANGE] adjust ajax request miss CSRF token behavior (@Hank)
* [CHANGE] revise funding round format (@Hank) 
* [REMOVE] remove project report (@Hank)

## v3.0.3 (2017-02-03)
* [FIXED] fix registration report data error (issue #56)

## v3.0.2 (2017-02-03)
* [NEW]   add TokenMismatchException handler (@Hank)
* [FIXED] editor upload image error (issue #55)

## v3.0.1 (2017-01-24)
* [NEW]    add verify csrf token middleware (@Hank)
* [NEW]    add js, css file checksum command (@Hank) 
* [CHANGE] upgrade laravel 5.2 to 5.3 (issue #53)
* [FIXED]  fix access token miss issue (issue #54)

## v3.0.0 (2017-01-20)
* [NEW]    new OAuth login (@Hank)
* [NEW]    login limit (issue #48)
* [NEW]    new duplicate login check (@Hank)
* [NEW]    create redis server (@Hank)
* [CHANGE] upgrade laravel 5.1 to 5.2 (@Hank)
* [CHANGE] revise action log (issue #45)
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
* [FIXED]  fix project referral data error (issue #47)
* [FIXED]  fix owner info error of solution edit page (issue #38)

## v2.6.6 (2016-10-26)
* [FIXED] fix uppercase/lowercase error of project uri file (@Hank) 

## v2.6.5 (2016-10-25)
* [CHANGE] integrate release project schedule API (@Hank)
* [CHANGE] integrate approve solution API (@Hank)
* [CHANGE] integrate approve expert API (@Hank)

## v2.6.4 (2016-10-18)
* [FIXED] fix AIT tour form datetime parse error (@Hank)

## v2.6.3 (2016-10-17)
* [FIXED] fix project report match search error (@Hank)
* [FIXED] fix owner user type error of solution update page (@Hank)

## v2.6.2 (2016-10-11)
* [CHANGE] improve performance (@Hank)
* [CHANGE] remove inbox model (@Hank)

## v2.6.1 (2016-09-23)
* [CHANGE] adjust AIT event email send status (@Hank)

## v2.6.0 (2016-09-14)
* [NEW] add AIT tour form report (@Hank)
* [CHANGE] adjust editor role access privileges (@Hank)
* [FIXED] fix landing expert operate issue (@Hank)

## v2.5.5 (2016-08-30)
* [FIXED] fix project's grade display of backend, frontend pm (@Hank)

## v2.5.4 (2016-08-29)
* [NEW] add action search of member and project (@Hank)
* [NEW] add schedule email not sent data of project (@Hank)
* [CHANGE] change column order of member and project (@Hank)
* [UPDATE] update project category (@Hank)

## v2.5.3 (2016-09-14)
* [FIXED] fix member,project,solution paginate error (#35)
* [FIXED] fix registration report data error (@Hank)
* [FIXED] fix user type filter error (@Hank)

## v2.5.2 (2016-08-5)
* [FIXED] fix solution update fail (@Hank)

## v2.5.1 (2016-08-5)
* [FIXED] remove email template code (@Hank)

## v2.5.0 (2016-08-4)
* [NEW] avoid cache js & css (@Hank)
* [NEW] new AIT q4 report (@Hank)
* [NEW] new float thead (@Hank)
* [REMOVE] remove email template (@Hank)

## v2.4.5 (2016-06-29)
* [FIXED] Fix project assigned PM search error (#33)

## v2.4.4 (2016-06-23)
* [FIXED] Fix landing expert unable to update expert list (#32)

## v2.4.3 (2016-06-20)
* [FIXED] Fix project update occur strengths column data format error (@Hank)
* [FIXED] Fix project update occur tag and resource column data format error (@Hank)
* [FIXED] Fix project detail company name link error (@Hank)
* [FIXED] Fix solution no have image update error (@Hank) 

## v2.4.2 (2016-06-13)
* [CHANGE] Revise manager head can change PM and Premium expert user type (@Hank)

## v2.4.1 (2016-06-08)
* [CHANGE] Change favicon.ico (@Hank)
* [FIXED]  Fix editor role browse member detail view error (@Hank)

## v2.4.0  (2016-06-03)
* [NEW]    New user_type premium-expert (@Hank) 
* [NEW]    New user internal memo function (@Hank)
* [NEW]    New hwtrek service api interface (@Hank)
* [NEW]    New user suspend function (@Hank)
* [CHANGE] Use string enum as user_type (@Keven)
* [CHANGE] modify project & member update behavior (@Hank) 
* [FIXED]  fix solution list data error (@Hank)

## v2.3.1 (2016-05-13)
* [CHANGE] modify solution search (@Hank)
* [CHANGE] modify member search (@Hank)
* [CHANGE] modify project report wording & add user referrals total number (@Hank)
* [FIXED]  fix backend team creator user not has hwtrek member column (@Hank)

## v2.3.0 (2016-05-11)
* [CHANGE] optimization project report & project (@Hank)
* [CHANGE] adjust proposed & referral dialog display (@Hank)
* [CHANGE] adjust session life time 6 hours (@Hank)
* [FIXED]  fix project referrals data error (@Hank)
* [FIXED]  fix editor style same forntend (@Hank)
* [FIXED]  fix member list & solution list search bug (@Hank)
* [NEW]    recommend expert send mail add collaborate pending list (@Hank)

## v2.2.0  (2016-04-21)
* [CHANGE] revise project function (@Hank)
* [CHANGE] revise project search function (@Hank) 
* [CHANGE] revise date time format (Apr 12, 2016) (@Hank)
* [CHANGE] revise project tech tag system (@Hank) 
* [NEW] add referrals & propose solution statistics (@Hank) 
* [NEW] add project report function (@Hank)


## v2.1.1 (2016-04-15)
* [FIXED] fix tour form user project id null issue (@Hank)

## v2.1.0 (2016-03-11)
* [NEW] add event questionnaire summary (@Hank)
* [CHANGE] adjust member view authorization of editor role (@Hank)
* [FIXED] fix event report note bug & select user issue (@Hank)

## v2.0.5 (2016-02-26)
* [FIXED] fix event report count error (@Hank)
* [NEW] add event report unique count information (@Hank)

## v2.0.4 (2016-02-25)
* [FIXED] fix event report incomplete data error (@Hank)

## v2.0.3 (2016-02-25)
* [FIXED] fix event report location error (@Hank)
* [CHANGE] adjust word count display (@Hank)

## v2.0.2 (2016-02-17)
* [FIXED] fix event report note function (@Hank)

## v2.0.1 (2016-02-02)
* [FIXED] fix solution limited (@Hank)

## v2.0.0 (2016-01-27)
* [NEW] add events report (@Hank)
* [FIXED] fix apply to be expert message empty issue (@Hank)

## v1.7.0 (2016-01-20)
* [NEW] add user attachment function in member (@Hank)
* [FIXED] fix editor upload image error (@Hank)

## v1.6.0 (2015-12-23)
* [NEW] creator upgrade expert (@Hank)
* [CHANGE] adjust comment report (@Hank)

## v1.5.9 (2015-12-16)
* [FIXED] fix hub questionnaires recommend expert bug (@Hank)

## v1.5.8 (2015-12-18)
* [FIXED] fix JSON.parse parse quote error from solution edit image (@Hank)
* [FIXED] fix solution edit, array not found thumb_delete_x index key error (@Hank)

## v1.5.7 (2015-11-09)
* [FIXED] fix purifier clean video not show (@Hank)
* [FIXED] fix .svg .bmp image upload error  (@Hank)

## v1.5.6 (2015-10-07)
* [FIXED] fix user update error (@Hank)

## v1.5.5 (2015-10-06)
* [FIXED] fix user update error (@Sylvia)

## v1.5.4 (2015-10-05)
* [NEW] new role editor (@Sylvia)
* [NEW] add operate log to log server (@Sylvia)
* [NEW] add project delete function (@Sylvia)

## v1.5.3 (2015-09-11)
* [CHANGE] comment report display (@Keven)
* [FIXED] change view of comment report (#16)
* [FIXED] add sender count to comment report (#17)
* [FIXED] schedule note function (#18)
* [FIXED] avoid edit user submit email index lost issue (@Hank)
