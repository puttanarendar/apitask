import unittest
import requests

class TestLoginAPI(unittest.TestCase):
    def setUp(self):
        self.base_url = 'http://localhost/app/api/';

    
    def test_login_successful(self):
        payload = {
            'email' : 'admin@gmail.com',
            'password':'password'
        }

        response = requests.post(f'{self.base_url}/login',data=payload)

        self.assertEqual(response.status_code, 200)

        data = response.json()

        # Check if the login was successful
        self.assertEqual(data['status'], 1)
        self.assertEqual(data['message'], 'Login success!')
        self.assertIsNotNone(data['access_token'])


    def test_login_failure(self):
        payload = {
            'email' : 'admin1@gmail.com',
            'password':'password'
        }

        response = requests.post(f'{self.base_url}/login',data=payload)

        self.assertEqual(response.status_code, 401)

        data = response.json()

        # Check if the login was successful
        self.assertEqual(data['status'], 0)
        self.assertEqual(data['message'], 'Wrong username or password.')


if __name__ == "__main__":
    unittest.main()

