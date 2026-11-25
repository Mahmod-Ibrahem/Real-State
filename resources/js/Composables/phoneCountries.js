import {getCountries, getCountryCallingCode, getExampleNumber, parsePhoneNumberFromString} from 'libphonenumber-js/min';
import examples from 'libphonenumber-js/mobile/examples';

const countryNames = new Intl.DisplayNames(['en'], {type: 'region'});

// Every country libphonenumber knows, by name: {id: 'EG', name: 'Egypt (+20)', code: '+20'}
export const PHONE_COUNTRIES = getCountries()
    .map((iso) => {
        const code = `+${getCountryCallingCode(iso)}`;

        return {id: iso, name: `${countryNames.of(iso) ?? iso} (${code})`, code};
    })
    .sort((a, b) => a.name.localeCompare(b.name));

// The number as it follows the country code, without a trunk prefix: "10 01234567" for +20 10 01234567
const withoutCallingCode = (number) => number.formatInternational().replace(`+${number.countryCallingCode} `, '');

export function examplePhone(country) {
    const example = getExampleNumber(country, examples);

    return example ? withoutCallingCode(example) : '';
}

// "01001234567" or "+20 100 123 4567" typed for Egypt becomes "10 01234567"; anything unparseable is kept as typed
export function toLocalPhone(value, country) {
    const number = parsePhoneNumberFromString(value ?? '', country);

    return number?.isValid() && number.country === country ? withoutCallingCode(number) : value;
}
