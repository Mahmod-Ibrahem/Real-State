<div style="max-width: 600px; margin: auto; font-family: Arial, sans-serif; border: 1px solid #ddd; border-radius: 8px; padding: 20px; background-color: #f9f9f9;">
    <h1 style="text-align: center; font-size: 24px; font-weight: 600; color: #ff6700; margin-bottom: 10px;">
        Qeima
    </h1>


    <p style="font-size: 14px; font-weight: 500; margin: 10px 0;">
    <strong style="color: orangered;">{{ $contactData['full_name'] }}</strong> wants to get in touch with you.
    </p>

    <strong style="color: orangered;">Subject: </strong> {{ $contactData['subject'] }}
    <p style="font-size: 14px; font-weight: 500; margin: 10px 0;">
    </p>

    <p style="font-size: 14px; font-weight: 500; margin: 10px 0;">
        <strong>Message:</strong> <span style="color: orangered;">{{ $contactData['message'] }}</span>
    </p>

    <p style="font-size: 14px; font-weight: 500; margin: 10px 0;">
        <strong>Phone:</strong> <span style="color: orangered;">{{ $contactData['phone'] }}</span>
    </p>

    <p style="font-size: 14px; font-weight: 500; margin: 10px 0;">
        <strong>Email:</strong> <span style="color: orangered;">{{ $contactData['email'] }}</span>
    </p>

    <hr style="margin: 20px 0; border: none; border-top: 1px solid #eee;" />
    <p style="text-align: center; font-size: 12px; color: #aaa;">This message was sent from your contact form.</p>
</div>
