from playwright.sync_api import sync_playwright

def run():
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        page = browser.new_page()

        print("Navigating to login page...")
        # Assuming Laravel Breeze or similar auth at /login
        try:
            page.goto("http://localhost:8000/login")

            # Check if we are on login page
            if "login" in page.url:
                print("Filling login form...")
                page.fill('input[name="email"]', 'test@example.com')
                page.fill('input[name="password"]', 'password')
                page.click('button[type="submit"]') # Or whatever the login button is
                page.wait_for_load_state('networkidle')

            print("Navigating to /user...")
            page.goto("http://localhost:8000/user")
            page.wait_for_load_state('networkidle')

            print("Taking screenshot...")
            page.screenshot(path="verification_screenshot.png", full_page=True)
            print("Screenshot saved to verification_screenshot.png")

        except Exception as e:
            print(f"Error: {e}")
            page.screenshot(path="error_screenshot.png")

        finally:
            browser.close()

if __name__ == "__main__":
    run()
