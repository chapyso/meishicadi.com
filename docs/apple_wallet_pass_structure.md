# Apple Wallet Pass Structure

## Updated `pass.json` Structure

The Apple Wallet pass now includes dynamic user content and proper Apple Wallet standards:

```json
{
  "formatVersion": 1,
  "passTypeIdentifier": "pass.com.meishicadi.vcard",
  "serialNumber": "123_1703123456",
  "teamIdentifier": "YOUR_TEAM_ID",
  "organizationName": "Meishicadi",
  "description": "Digital Business Card - John Doe",
  "logoText": "John Doe",
  "generic": {
    "primaryFields": [
      {
        "key": "name",
        "label": "NAME",
        "value": "John Doe"
      }
    ],
    "secondaryFields": [
      {
        "key": "occupation",
        "label": "OCCUPATION",
        "value": "Software Engineer"
      },
      {
        "key": "company",
        "label": "COMPANY",
        "value": "Tech Corp"
      }
    ],
    "auxiliaryFields": [
      {
        "key": "phone",
        "label": "PHONE",
        "value": "+1-555-123-4567"
      },
      {
        "key": "email",
        "label": "EMAIL",
        "value": "john.doe@example.com"
      }
    ]
  },
  "barcode": {
    "format": "PKBarcodeFormatQR",
    "message": "https://ourdomain.com/vcard/johndoe",
    "messageEncoding": "iso-8859-1"
  },
  "nfc": {
    "message": "https://ourdomain.com/vcard/johndoe",
    "encryptionPublicKey": "YOUR_NFC_PUBLIC_KEY"
  },
  "webServiceURL": "https://ourdomain.com/wallet/verify/123",
  "authenticationToken": "generated_auth_token",
  "backgroundColor": "#007AFF",
  "foregroundColor": "#FFFFFF",
  "labelColor": "#8E8E93",
  "logo": {
    "filename": "logo.png",
    "needsAlpha": true
  },
  "icon": {
    "filename": "icon.png",
    "needsAlpha": true
  },
  "strip": {
    "filename": "strip.png",
    "needsAlpha": true
  }
}
```

## Dynamic Fields from User Profile

### 1. **User Information**
- `name`: User's full name from vCard
- `occupation`: User's job title
- `company`: User's company name
- `phone`: User's phone number
- `email`: User's email address

### 2. **Brand Colors**
- `backgroundColor`: User's template background color
- `foregroundColor`: User's template text color
- `labelColor`: User's template label color

### 3. **Images**
- `logo`: User's profile image (160x50px)
- `strip`: User's profile image (320x123px)
- `icon`: User's profile image (29x29px)

### 4. **QR Code & NFC**
- `barcode.message`: User's unique vCard URL
- `nfc.message`: Same vCard URL for tap-to-share

## File Structure

The `.pkpass` file contains:

```
pass.pkpass (ZIP file)
├── pass.json          # Main pass configuration
├── manifest.json      # File hashes for verification
├── signature          # Digital signature
├── logo.png          # User's logo (160x50)
├── strip.png         # User's strip image (320x123)
└── icon.png          # User's icon (29x29)
```

## Image Processing

### Apple Wallet Image Standards:
- **Logo**: 160x50px, PNG with alpha channel
- **Strip**: 320x123px, PNG with alpha channel  
- **Icon**: 29x29px, PNG with alpha channel

### Processing Steps:
1. Download user's profile image
2. Resize to Apple Wallet dimensions
3. Enable alpha blending for transparency
4. Compress with maximum quality
5. Save as PNG format

## NFC Functionality

When users tap their iPhone on another iPhone:
1. NFC payload contains the vCard URL
2. Recipient's iPhone opens the URL
3. Shows the full vCard profile
4. Enables easy contact sharing

## QR Code Integration

The QR code contains:
- User's unique vCard URL
- Scannable by any QR code reader
- Opens the full vCard profile
- Enables easy contact sharing

## Configuration Requirements

### Environment Variables:
```env
APPLE_TEAM_ID=your_team_id
APPLE_PASS_TYPE_IDENTIFIER=pass.com.meishicadi.vcard
APPLE_NFC_PUBLIC_KEY=your_nfc_public_key
APPLE_CERTIFICATE_PATH=/path/to/certificate.p12
APPLE_CERTIFICATE_PASSWORD=your_certificate_password
```

### Production Requirements:
1. Apple Developer Account
2. Pass Type ID certificate
3. NFC public key for tap-to-share
4. Proper certificate signing
5. HTTPS hosting for .pkpass files

## Usage Flow

1. **User clicks "Add to Apple Wallet"**
2. **System generates personalized pass**
3. **Images are processed to Apple standards**
4. **QR code and NFC payload are added**
5. **Pass is signed and compressed**
6. **User downloads .pkpass file**
7. **iPhone automatically opens Apple Wallet**
8. **Pass is added to user's wallet**
9. **Tap-to-share and QR scanning work immediately** 