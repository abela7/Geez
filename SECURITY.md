# Security Guidelines

## Defaults & Best Practices

### Environment & Secrets
- **No secrets in repository**: `.env` files are gitignored
- **No PII in logs**: Personal data is masked in application logs
- **HTTPS in production**: All production traffic must use HTTPS
- **Secure headers**: CSP, HSTS, and other security headers enabled

### Authentication & Authorization
- **2FA for remote access**: Required for admin accounts
- **Role-based access**: Spatie Laravel Permission package
- **Session security**: Secure, HTTP-only cookies
- **CSRF protection**: Enabled on all forms

### Data Protection
- **Input validation**: All user input validated and sanitized
- **SQL injection prevention**: Parameter binding only, no raw queries
- **XSS protection**: Output escaping, CSP headers
- **File upload security**: Validated file types and sizes

### Infrastructure
- **On-premise primary**: LAN-based source of truth
- **VPN access**: Secure remote access via WireGuard/Tailscale
- **Regular backups**: Nightly automated backups
- **Audit logging**: All sensitive actions logged

### Development
- **No secrets in code**: Use environment variables
- **Dependency scanning**: Regular security updates
- **Code review**: Security-focused PR reviews
- **Least privilege**: Minimal required permissions

## Reporting Security Issues

Report security vulnerabilities to the project maintainers. Do not disclose publicly until resolved.
