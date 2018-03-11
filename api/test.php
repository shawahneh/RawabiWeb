
Rawabi API Document

Method #1 : userAuth
INPUT:
    action = userAuth
    username
    password
OUTPUT:
Success return :
    {auth:true}
Fail return :
    {auth:false}
-----------------------
Method #2 : userRegister
INPUT:
    action = userRegister
    username
    password
    fullname
    gender
    birthdate
    address
    userType
    image
    phone
OUTPUT:
Success return :
    {registration:success}
Fail return :
    {registration:failed}
---------------------
Method #3 : getJourneys
INPUT:
    action = getJourneys
    username
    password
    userId
    start
    num
OUTPUT:
Success Return:
    {
    journeys: [
                {
                    id:
                    startLocationX:
                    startLocationY:
                    endLocationX:
                    endLocationY:
                    goingDate:
                    seats:
                    genderPrefer:
                    carDescription:
                    user: {
                            id:
                            username:
                            fullname:
                            gender:
                            birthdate:
                            address:
                            userType:
                            image:
                            phone:
                          }
                }
              ]
    }
Fail Return:
    When there is no journeys :
        {
            journeys:[]
        }
    When the username or the password is wrong return :
        {
            auth:false
        }
-------------------------------------
Method #4 : getRides
INPUTS:
    username
    password
    userId // if userId <=0 then it will set the userId for the logged in user the one who has the username and password
    start
    num
OUTPUT:
Success Return :
    {

    }
Fail Return :
    When there is no Rides :
    {

    }
    When the username or the password is wrong return :
    {
        auth:false
    }
