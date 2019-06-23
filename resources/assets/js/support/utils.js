
import moment from 'moment'

export default {
    toHumanDate(dateTime) {
        return moment.utc(dateTime).fromNow();
    }
}