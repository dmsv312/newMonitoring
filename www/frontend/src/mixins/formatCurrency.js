export default {
    methods: {
        formatCurrency(value) {
            value = parseFloat(value);
            if (isNaN(value)) return '';
            let result = Math.abs(value);
            result = result.toString();
            result = result.replace(/\./,',');
            if (value > 0) result = '+ ' + result;
            if (value < 0) result = '- ' + result;
            result += '₽';

            return result;
        }
    }
}