import unittest
import requests
import json
from unittest.mock import patch


class GroupApiTestCase(unittest.TestCase):
    base_url = 'http://localhost/app/api/'

    def setUp(self):
        # Perform login and extract the access token
        login_data = {
            "email": "Pavan@gmail.com",
            "password": "password"
        }
        login_response = requests.post(f'{self.base_url}/login', data=login_data)
        self.assertEqual(login_response.status_code, 200)
        login_response_data = login_response.json()
        self.access_token = login_response_data.get('access_token', '')

    def test_create_group(self):
        # Simulate creating a group
        data = {
            'name': 'Test Group235'
        }
        headers = {
            'Authorization': f'{self.access_token}'  # Use the extracted access token
        }
        response = requests.post(f'{self.base_url}/group', data=data, headers=headers)

        # Check if the response status code is 200 (OK)
        self.assertEqual(response.status_code, 200)

        # Check if the response contains expected data
        response_data = response.json()
        print(response_data)
        self.assertTrue('status' in response_data)
        self.assertTrue('message' in response_data)
        self.assertEqual(response_data['status'], 1)
        self.assertEqual(response_data['message'], 'Group Added Successfully!')


    def test_invalid_create_group(self):
        # Simulate creating a group
        data = {
            'name': 'Test Group235'
        }
        headers = {
            'Authorization': f'{self.access_token}'  # Use the extracted access token
        }
        response = requests.post(f'{self.base_url}/group', data=data, headers=headers)

        # Check if the response status code is 200 (OK)
        self.assertEqual(response.status_code, 400)

        # Check if the response contains expected data
        response_data = response.json()
        self.assertTrue('status' in response_data)
        self.assertTrue('message' in response_data)
        self.assertEqual(response_data['status'], 0)
        self.assertEqual(response_data['message'], 'The Name field must contain a unique value.')


    def test_delete_group(self):
        # Simulate creating a group
        group_id = 6  # Replace with a valid group ID
        headers = {
            'Authorization': f'{self.access_token}'  # Use the extracted access token
        }

        # Mock the response of the form_validation
        with patch('requests.post') as mock_post:
            # Simulate a successful validation
            mock_post.return_value.status_code = 200
            mock_post.return_value.text = '{"status": 1, "message": "Validation Passed"}'

            # Make the DELETE request to the API
            response = requests.delete(f'{self.base_url}/group/{group_id}', headers=headers)

            # Check if the response status code is 200 (OK)
            self.assertEqual(response.status_code, 200)

            # Check if the response contains expected data
            response_data = response.json()
            self.assertTrue('status' in response_data)
            self.assertTrue('message' in response_data)
            self.assertEqual(response_data['status'], 1)
            self.assertEqual(response_data['message'], 'Group Deleted Successfully!')

    def test_delete_invalid_group(self):
        group_id = 6  # Replace with a valid group ID
        headers = {
            'Authorization': f'{self.access_token}'  # Use the extracted access token
        }

        # Mock the response of the form_validation
        with patch('requests.post') as mock_post:
            # Simulate a successful validation
            mock_post.return_value.status_code = 200
            mock_post.return_value.text = '{"status": 1, "message": "Validation Passed"}'

            # Make the DELETE request to the API
            response = requests.delete(f'{self.base_url}/group/{group_id}', headers=headers)

            # Check if the response status code is 200 (OK)
            self.assertEqual(response.status_code, 400)

            # Check if the response contains expected data
            response_data = response.json()
            self.assertTrue('status' in response_data)
            self.assertTrue('message' in response_data)
            self.assertEqual(response_data['status'], 1)
            self.assertEqual(response_data['message'], 'There was a problem deleting your  group. Please try again.!')


    def test_group_search_success(self):
        # Simulate a keyword for group search
        keyword = 'b'

        # Send a POST request to the group_search endpoint with the access token and keyword
        headers = {
            'Authorization': f'{self.access_token}'
        }
        data = {
            'keyword': keyword
        }
        response = requests.post(f'{self.base_url}/group-search', headers=headers, data=data)

        # Check if the response status code is 200 (OK)
        self.assertEqual(response.status_code, 200)

        # Check if the response contains expected data
        response_data = response.json()
        self.assertTrue('status' in response_data)
        self.assertTrue('message' in response_data)
        self.assertTrue('data' in response_data)
        self.assertEqual(response_data['status'], 1)
        self.assertEqual(response_data['message'], 'Group List Found!')

    def test_group_search_no_results(self):
        # Simulate a keyword for group search that returns no results
        keyword = 'sa'

        # Send a POST request to the group_search endpoint with the access token and keyword
        headers = {
            'Authorization': f'{self.access_token}'
        }
        data = {
            'keyword': keyword
        }
        response = requests.post(f'{self.base_url}/group-search', headers=headers, data=data)

        # Check if the response status code is 400 (Bad Request)
        self.assertEqual(response.status_code, 400)

        # Check if the response contains expected data
        response_data = response.json()
        self.assertTrue('status' in response_data)
        self.assertTrue('message' in response_data)
        self.assertTrue('data' in response_data)
        self.assertEqual(response_data['status'], 0)
        self.assertEqual(response_data['message'], 'No Group List Found')

    def test_add_member_success(self):
        # Simulate group and user IDs
        group_id = '3'
        user_id = '11'

        # Send a POST request to the add_member endpoint with the access token, group ID, and user ID
        headers = {
            'Authorization': f'{self.access_token}'
        }
        data = {
            'group_id': group_id,
            'user_id': user_id
        }
        response = requests.post(f'{self.base_url}/add-member', headers=headers, data=data)

        # Check if the response status code is 200 (OK)
        self.assertEqual(response.status_code, 200)

        # Check if the response contains expected data
        response_data = response.json()
        self.assertTrue('status' in response_data)
        self.assertTrue('message' in response_data)
        self.assertTrue('data' in response_data)
        self.assertEqual(response_data['status'], 1)
        self.assertEqual(response_data['message'], 'Member Added Successfully!')

    def test_add_member_user_not_exist(self):
        # Simulate a group ID and a non-existent user ID
        group_id = '1'
        user_id = '1000'  # Non-existent user ID

        # Send a POST request to the add_member endpoint with the access token, group ID, and user ID
        headers = {
            'Authorization': f'{self.access_token}'
        }
        data = {
            'group_id': group_id,
            'user_id': user_id
        }
        response = requests.post(f'{self.base_url}/add-member', headers=headers, data=data)

        # Check if the response status code is 400 (Bad Request)
        self.assertEqual(response.status_code, 400)

        # Check if the response contains expected data
        response_data = response.json()
        self.assertTrue('status' in response_data)
        self.assertTrue('message' in response_data)
        self.assertTrue('data' in response_data)
        self.assertEqual(response_data['status'], 0)
        self.assertEqual(response_data['message'], "User id doesn't exist.")

    def test_send_message_success(self):
        # Simulate group and user IDs
        group_id = '2'
        user_id = '15'

        # Simulate a message
        message = 'Hello, this is a test message.'

        # Send a POST request to the send_message endpoint with the access token, group ID, user ID, and message
        headers = {
            'Authorization': f'{self.access_token}'
        }
        data = {
            'group_id': group_id,
            'user_id': user_id,
            'message': message
        }
        response = requests.post(f'{self.base_url}/send-message', headers=headers, data=data)

        # Check if the response status code is 200 (OK)
        self.assertEqual(response.status_code, 200)

        # Check if the response contains expected data
        response_data = response.json()
        self.assertTrue('status' in response_data)
        self.assertTrue('message' in response_data)
        self.assertTrue('data' in response_data)
        self.assertEqual(response_data['status'], 1)
        self.assertEqual(response_data['message'], 'Message Added Successfully!')

    def test_send_message_user_not_exist(self):
        # Simulate group and user IDs
        group_id = '1'
        user_id = '1000'  # Non-existent user ID

        # Simulate a message
        message = 'Hello, this is a test message.'

        # Send a POST request to the send_message endpoint with the access token, group ID, user ID, and message
        headers = {
            'Authorization': f'{self.access_token}'
        }
        data = {
            'group_id': group_id,
            'user_id': user_id,
            'message': message
        }
        response = requests.post(f'{self.base_url}/send-message', headers=headers, data=data)

        # Check if the response status code is 400 (Bad Request)
        self.assertEqual(response.status_code, 400)
        # Check if the response contains expected data
        response_data = response.json()
        self.assertTrue('status' in response_data)
        self.assertTrue('message' in response_data)
        self.assertTrue('data' in response_data)
        self.assertEqual(response_data['status'], 0)
        self.assertEqual(response_data['message'], "User id doesn't exist.")


    def test_send_message_success(self):
        # Simulate a valid access token (replace with a valid token)
        access_token = 'YOUR_ACCESS_TOKEN'

        # Simulate group and user IDs
        group_id = '1'
        user_id = '2'

        # Simulate a message
        message = 'Hello, this is a test message.'

        # Send a POST request to the send_message endpoint with the access token, group ID, user ID, and message
        headers = {
            'Authorization': f'Bearer {access_token}'
        }
        data = {
            'group_id': group_id,
            'user_id': user_id,
            'message': message
        }
        response = requests.post(f'{self.base_url}/send_message', headers=headers, data=data)

        # Check if the response status code is 200 (OK)
        self.assertEqual(response.status_code, 200)

        # Check if the response contains expected data
        response_data = response.json()
        self.assertTrue('status' in response_data)
        self.assertTrue('message' in response_data)
        self.assertTrue('data' in response_data)
        self.assertEqual(response_data['status'], 1)
        self.assertEqual(response_data['message'], 'Message Added Successfully!')

    def test_send_message_user_not_exist(self):
        # Simulate group and user IDs
        group_id = '1'
        user_id = '1000'  # Non-existent user ID
        # Simulate a message
        message = 'Hello, this is a test message.'
        # Send a POST request to the send_message endpoint with the access token, group ID, user ID, and message
        headers = {
            'Authorization': f'{self.access_token}'
        }
        data = {
            'group_id': group_id,
            'user_id': user_id,
            'message': message
        }
        response = requests.post(f'{self.base_url}/send_message', headers=headers, data=data)

        # Check if the response status code is 400 (Bad Request)
        self.assertEqual(response.status_code, 400)

        # Check if the response contains expected data
        response_data = response.json()
        self.assertTrue('status' in response_data)
        self.assertTrue('message' in response_data)
        self.assertTrue('data' in response_data)
        self.assertEqual(response_data['status'], 0)
        self.assertEqual(response_data['message'], "User id doesn't exist.")

    def test_add_like_message_success(self):
        # Simulate group, user, and message IDs
        group_id = '2'
        user_id = '15'
        message_id = '4'  # Non-existent message ID

        # Send a POST request to the add_like_message endpoint with the access token, group ID, user ID, and message ID
        headers = {
            'Authorization': f'{self.access_token}'
        }
        data = {
            'group_id': group_id,
            'user_id': user_id,
            'message_id': message_id
        }
        response = requests.post(f'{self.base_url}/add-like-message', headers=headers, data=data)

        # Check if the response status code is 200 (OK)
        self.assertEqual(response.status_code, 200)

        # Check if the response contains expected data
        response_data = response.json()
        self.assertTrue('status' in response_data)
        self.assertTrue('message' in response_data)
        self.assertTrue('data' in response_data)
        self.assertEqual(response_data['status'], 1)
        self.assertEqual(response_data['message'], 'Message Was Liked Successfully!')

    def test_add_like_message_user_not_exist(self):
        # Simulate group, user, and message IDs
        group_id = '2'
        user_id = '15'
        message_id = '4'  # Non-existent message ID

        # Send a POST request to the add_like_message endpoint with the access token, group ID, user ID, and message ID
        headers = {
            'Authorization': f'{self.access_token}'
        }
        data = {
            'group_id': group_id,
            'user_id': user_id,
            'message_id': message_id
        }
        response = requests.post(f'{self.base_url}/add-like-message', headers=headers, data=data)

        # Check if the response status code is 400 (Bad Request)
        self.assertEqual(response.status_code, 400)

        # Check if the response contains expected data
        response_data = response.json()
        self.assertTrue('status' in response_data)
        self.assertTrue('message' in response_data)
        self.assertTrue('data' in response_data)
        self.assertEqual(response_data['status'], 0)
        self.assertEqual(response_data['message'], "User id doesn't exist.")

    def test_add_like_message_message_not_exist(self):
        # Simulate group, user, and message IDs
        group_id = '2'
        user_id = '15'
        message_id = '4'  # Non-existent message ID

        # Send a POST request to the add_like_message endpoint with the access token, group ID, user ID, and message ID
        headers = {
            'Authorization': f'{self.access_token}'
        }
        data = {
            'group_id': group_id,
            'user_id': user_id,
            'message_id': message_id
        }
        response = requests.post(f'{self.base_url}/add-like-message', headers=headers, data=data)

        # Check if the response status code is 400 (Bad Request)
        self.assertEqual(response.status_code, 400)

        # Check if the response contains expected data
        response_data = response.json()
        self.assertTrue('status' in response_data)
        self.assertTrue('message' in response_data)
        self.assertTrue('data' in response_data)
        self.assertEqual(response_data['status'], 0)
        self.assertEqual(response_data['message'], "Message id doesn't exist.")

    def test_add_like_message_user_cannot_like(self):
        # Simulate group, user, and message IDs
        group_id = '1'
        user_id = '2'
        message_id = '3'

        # Send a POST request to the add_like_message endpoint with the access token, group ID, user ID, and message ID
        headers = {
            'Authorization': f'{self.access_token}'
        }
        data = {
            'group_id': group_id,
            'user_id': user_id,
            'message_id': message_id
        }
        response = requests.post(f'{self.base_url}/add-like-message', headers=headers, data=data)

        # Check if the response status code is 400 (Bad Request)
        self.assertEqual(response.status_code, 400)

        # Check if the response contains expected data
        response_data = response.json()
        self.assertTrue('status' in response_data)
        self.assertTrue('message' in response_data)
        self.assertTrue('data' in response_data)
        self.assertEqual(response_data['status'], 0)
        self.assertEqual(response_data['message'], "Logined user can't like the message.")


if __name__ == "__main__":
    unittest.main()
