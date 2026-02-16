<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', config('app.name'))</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
</head>
<body style="margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif; background-color: #f3f4f6; -webkit-font-smoothing: antialiased;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f3f4f6;">
        <tr>
            <td align="center" style="padding: 32px 16px;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="max-width: 600px; background-color: #ffffff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
                    {{-- Header: Logo --}}
                    <tr>
                        <td align="center" style="padding: 32px 24px 24px;">
                            <a href="{{ config('app.url') }}" target="_blank" rel="noopener">
                                <img src="{{ branding_logo_url('default') }}" alt="{{ config('app.name') }}" width="160" height="48" style="display: block; max-width: 160px; height: auto;" />
                            </a>
                        </td>
                    </tr>
                    {{-- Content --}}
                    <tr>
                        <td style="padding: 0 32px 24px; color: #374151; font-size: 16px; line-height: 1.6;">
                            @yield('content')
                        </td>
                    </tr>
                    {{-- CTA Button (optional) --}}
                    @hasSection('button')
                    <tr>
                        <td align="center" style="padding: 0 32px 32px;">
                            @yield('button')
                        </td>
                    </tr>
                    @endif
                    {{-- Footer --}}
                    <tr>
                        <td style="padding: 24px 32px 32px; border-top: 1px solid #e5e7eb; color: #6b7280; font-size: 12px; line-height: 1.6;">
                            @php
                                $companyAddress = setting('company_address') ?? setting('company_legal_name', config('app.name'));
                                $facebook = setting('homepage_social_facebook');
                                $instagram = setting('homepage_social_instagram');
                                $linkedin = setting('homepage_social_linkedin');
                            @endphp
                            @if($companyAddress)
                                <p style="margin: 0 0 8px;">{{ $companyAddress }}</p>
                            @endif
                            @if($facebook || $instagram || $linkedin)
                                <p style="margin: 0 0 8px;">
                                    @if($facebook)<a href="{{ $facebook }}" style="color: #4f46e5; text-decoration: none;">Facebook</a>@endif
                                    @if($facebook && $instagram) &bull; @endif
                                    @if($instagram)<a href="{{ $instagram }}" style="color: #4f46e5; text-decoration: none;">Instagram</a>@endif
                                    @if(($facebook || $instagram) && $linkedin) &bull; @endif
                                    @if($linkedin)<a href="{{ $linkedin }}" style="color: #4f46e5; text-decoration: none;">LinkedIn</a>@endif
                                </p>
                            @endif
                            <p style="margin: 16px 0 0; color: #9ca3af;">Você recebeu este e-mail porque faz parte da elite Vertex Contas.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
