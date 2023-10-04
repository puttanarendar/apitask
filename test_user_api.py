import unittest
import requests

class UserControllerE2ETestCase(unittest.TestCase):
    base_url = 'http://localhost/app/api/'

    def setUp(self):
        # Perform login and extract the access token
        login_data = {
            "email": "admin@gmail.com",
            "password": "password"
        }
        login_response = requests.post(f'{self.base_url}/login', data=login_data)
        self.assertEqual(login_response.status_code, 200)
        login_response_data = login_response.json()
        self.access_token = login_response_data.get('access_token', '')

    def test_register_user(self):
        # Simulate registering a user
        data = {
            'username': 'testuser4212',
            'email': 'testuser4212@example.com',
            'password': 'testpassword',
            'phone': '0618111234',
            'dob': '1990-01-01',
            'is_admin': 0  # Assuming a non-admin user
        }
        response = requests.post(f'{self.base_url}/user', data=data)

        # Check if the response status code is 200 (OK)
        self.assertEqual(response.status_code, 200)

        # Check if the response contains expected data
        response_data = response.json()
        self.assertTrue('status' in response_data)
        self.assertTrue('message' in response_data)
        self.assertEqual(response_data['status'], 1)
        self.assertEqual(response_data['message'], 'Thank you for registering your new account!')

    def test_update_user(self):
        # Simulate updating a user's details
        user_id = 17  # Replace with an existing user ID
        data = {
            'username': 'updateduser',
            'email': 'updateduser@gmail.com',
            'password': 'updatedpassword',
            'dob': '1990-02-02'  # Update the date of birth
        }
        headers = {
            'Authorization': f'{self.access_token}'  # Replace with a valid access token
        }
        response = requests.put(f'{self.base_url}/user/{user_id}', json=data, headers=headers)

        # Check if the response status code is 200 (OK)
        self.assertEqual(response.status_code, 200)

        # Check if the response contains expected data
        response_data = response.json()
        self.assertTrue('status' in response_data)
        self.assertTrue('message' in response_data)
        self.assertEqual(response_data['status'], 1)
        self.assertEqual(response_data['message'], 'User updated successfully!')

if __name__ == "__main__":
    unittest.main()
