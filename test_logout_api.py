import unittest
import requests
import json

class LogoutPostTestCase(unittest.TestCase):
    base_url = 'http://localhost/app/api/'

    def test_logout_success(self):
        # Simulate a valid access token (replace with a valid token)
        access_token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1aWQiOiIxIiwidXNlcm5hbWUiOiJhZG1pbiIsImlzX2FkbWluIjoiMSIsIkFQSV9USU1FIjoxNjk2MzUyNTYwfQ.7dAOtBihuaH68c2iEVjO_pPIXLXXcgbCJJi1zk5tJpQ'

        # Send a POST request to the logout endpoint with the access token
        headers = {
            'Authorization': f'{access_token}'
        }
        response = requests.post(f'{self.base_url}/logout', headers=headers)
        print(response.json())

        # Check if the response status code is 200 (OK)
        self.assertEqual(response.status_code, 200)

        # Check if the response contains expected data
        response_data = response.json()
        self.assertTrue('status' in response_data)
        self.assertTrue('message' in response_data)
        self.assertEqual(response_data['status'], 1)
        self.assertEqual(response_data['message'], 'Logout!')

    def test_logout_invalid_token(self):
        # Simulate an invalid access token (replace with an invalid token)
        access_token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1aWQiOiIxIiwidXNlcm5hbWUiOiJhZG1pbiIsImlzX2FkbWluIjoiMSIsIkFQSV9USU1FIjoxNjk2MzUyNTA1fQ.mHU0VPij7G9E5FnODsUz94lV8hrBKJV9rJFmkZG1naA'

        # Send a POST request to the logout endpoint with the invalid token
        headers = {
            'Authorization': f'{access_token}'
        }
        response = requests.post(f'{self.base_url}/logout', headers=headers)
        print(response.json())

        # Check if the response status code is 400 (Bad Request)
        self.assertEqual(response.status_code, 400)

        # Check if the response contains expected data
        response_data = response.json()
        self.assertTrue('status' in response_data)
        self.assertTrue('message' in response_data)
        self.assertEqual(response_data['status'], 0)
        self.assertEqual(response_data['message'], 'Token is invalid.')

if __name__ == "__main__":
    unittest.main()
