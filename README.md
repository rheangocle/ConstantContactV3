# Constant Contact V3 PHP Example

An example of implementing Constant Contact version 3 with functionality for OAuth and email creation/scheduling. This is what worked for us when I was trying to update our code to work with version 3. This example works with OAuth 2 Authorization Code Flow. 

## OAuth 2 and Authorization

- Constant Contact OAuth2 overview: <https://developer.constantcontact.com/api_guide/auth_overview.html>
  - Ensure that you have selected OAuth2 Authorization Code Flow if you are following these examples. In addition,we are using a long-lived refresh token, so if you are using a short lived refresh token, the methods will be slightly different but most things should still apply.
- Update or create new application
- Get client Id and client secret (save to db or env)
- Choose type of auth
- Use client Id and client secret to get access token and refresh token
- Access tokens and short-lived refresh tokens expire after 24 hours.
- Follow CC Examples or go to Postman to obtain auth code.

## Contact Lists



## Contact

Feel free to contact me with any questions at rheangocle@gmail.com or on here. Thank you. 