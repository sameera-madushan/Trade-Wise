import imageCompression from 'browser-image-compression'

/**
 * Check if Two Arrays have the Same Elements
 *
 * @param {Array} array1
 * @param {Array} array2
 * @returns boolean
 */
export function areEqual(array1, array2) {
	if (array1.length !== array2.length) return false
	return array1.every((element, index) => {
		if (element === array2[index]) {
			return true
		}
		return false
	})
}

/**
 * Get cookie value from passed key
 *
 * @returns string
 */
export function getCookie(key = 'XSRF-TOKEN') {
	var match = document.cookie.match(new RegExp(`(^| )${key}=([^;]+)`))
	return match ? match[2] : ''
}

/**
 * JavaScript file object to base64 image
 *
 * @param {Blob} blob
 * @returns {String} base64Image
 */
export const blobToBase64 = async (blob) => {
	return new Promise((resolve, reject) => {
		try {
			const reader = new FileReader()
			reader.onloadend = () => resolve(reader.result)
			reader.readAsDataURL(blob)
		} catch (error) {
			reject(error)
		}
	})
}

/**
 * JavaScript data uri to File object
 *
 * @param {String} dataURI
 * @returns {File} file
 */
export const dataURItoFile = (dataURI) => {
	// convert base64/URLEncoded data component to raw binary data held in a string
	var byteString
	if (dataURI.split(',')[0].indexOf('base64') >= 0) byteString = atob(dataURI.split(',')[1])
	else byteString = unescape(dataURI.split(',')[1])

	// separate out the mime component
	var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0]

	// write the bytes of the string to a typed array
	var ia = new Uint8Array(byteString.length)
	for (var i = 0; i < byteString.length; i++) {
		ia[i] = byteString.charCodeAt(i)
	}

	let fileName = new Date().getTime() + '.jpg'

	return new File([ia], fileName, { type: mimeString })
}

/**
 * Compress Images (an async function)
 *
 * @param {File} file
 * @param {Object} data (optional)
 * @returns {Blob} compressedFile
 */
export const compressedImage = async (file, { maxSizeMB = 1, maxWidthOrHeight = 1920 } = {}) => {
	try {
		let compressedFile = await imageCompression(file, {
			maxSizeMB: maxSizeMB,
			maxWidthOrHeight: maxWidthOrHeight,
		})
		return compressedFile
	} catch (error) {
		// eslint-disable-next-line no-console
		console.error(error)
	}
}

/**
 * Get default image size for cropper
 *
 * @param {String} imageSize
 * @returns {Object}
 */
export const defaultSize = ({ imageSize }) => {
	return {
		width: Math.min(imageSize.height, imageSize.width),
		height: Math.min(imageSize.height, imageSize.width),
	}
}

/**
 * Replace all digits except last three with *
 *
 * @param {String} value
 * @return {String} *******1234
 */
export const maskedString = (value) => {
	if (!value) return
	if (value.length < 4) {
		return value
	}
	let str = ''
	for (let index = 0; index < value.length - 3; index++) {
		str += '*'
	}
	str += value.substr(value.length - 3)
	return str
}

/**
 * Format bytes as human-readable text.
 *
 * @param bytes Number of bytes.
 * @param si True to use metric (SI) units, aka powers of 1000. False to use
 *           binary (IEC), aka powers of 1024.
 * @param dp Number of decimal places to display.
 *
 * @return Formatted string.
 */
export const humanFileSize = (bytes, si = false, dp = 1) => {
	const thresh = si ? 1000 : 1024

	if (Math.abs(bytes) < thresh) {
		return bytes + ' B'
	}

	const units = si ? ['kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'] : ['KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB']
	let u = -1
	const r = 10 ** dp

	do {
		bytes /= thresh
		++u
	} while (Math.round(Math.abs(bytes) * r) / r >= thresh && u < units.length - 1)

	return bytes.toFixed(dp) + ' ' + units[u]
}

const MONTH_NAMES = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']

/**
 * Fromat date as human-readable text.
 *
 * @param {boolean|string} prefomattedDate
 * @param {boolean} hideYear
 * @returns {string} formatted date string
 */
export function getFormattedDate(date, prefomattedDate = false, hideYear = false) {
	const day = date.getDate()
	const month = MONTH_NAMES[date.getMonth()]
	const year = date.getFullYear()
	let hours = date.getHours()
	let minutes = date.getMinutes()

	var ampm = hours >= 12 ? 'PM' : 'AM'
	hours = hours % 12
	hours = hours ? hours : 12 // the hour '0' should be '12'
	minutes = minutes < 10 ? '0' + minutes : minutes

	if (minutes < 10) {
		// Adding leading zero to minutes
		minutes = `0${minutes}`
	}

	if (prefomattedDate) {
		// Today at 10:20
		// Yesterday at 10:20
		return `${prefomattedDate} at ${hours}:${minutes} ${ampm}`
	}

	if (hideYear) {
		// 10. January at 10:20
		return `${day}. ${month} at ${hours}:${minutes} ${ampm}`
	}

	// 10. January 2017. at 10:20
	return `${day}. ${month} ${year}. at ${hours}:${minutes} ${ampm}`
}

/**
 * Fromat date time as time ago text.
 *
 * @source https://muffinman.io/blog/javascript-time-ago-function/
 *
 * @param {Date|string} dateParam
 * @returns {string} time ago string
 */
export function timeAgo(dateParam) {
	if (!dateParam) {
		return null
	}

	const date = typeof dateParam === 'object' ? dateParam : new Date(dateParam)
	const DAY_IN_MS = 86400000 // 24 * 60 * 60 * 1000
	const today = new Date()
	const yesterday = new Date(today - DAY_IN_MS)
	const seconds = Math.round((today - date) / 1000)
	const minutes = Math.round(seconds / 60)
	const hours = Math.round(minutes / 60)
	const isToday = today.toDateString() === date.toDateString()
	const isYesterday = yesterday.toDateString() === date.toDateString()
	const isThisYear = today.getFullYear() === date.getFullYear()

	if (seconds < 5) {
		return 'now'
	} else if (seconds < 60) {
		return `${seconds} seconds ago`
	} else if (seconds < 90) {
		return 'about a minute ago'
	} else if (minutes < 60) {
		return `${minutes} minutes ago`
	} else if (hours == 1) {
		return `${hours} hour ago`
	} else if (hours < 5) {
		return `${hours} hours ago`
	} else if (isToday) {
		return getFormattedDate(date, 'Today') // Today at 10:20
	} else if (isYesterday) {
		return getFormattedDate(date, 'Yesterday') // Yesterday at 10:20
	} else if (isThisYear) {
		return getFormattedDate(date, false, true) // 10. January at 10:20
	}

	return getFormattedDate(date) // 10. January 2017. at 10:20
}
