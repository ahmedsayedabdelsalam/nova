import axios from 'axios'

export default {
    'images' : {
        'all' : queryParams => axios.get('/nova-vendor/media-library/images',{
            params:queryParams
        }),
        'delete': (imageId) => axios.delete(
            `/nova-vendor/media-library/delete-media/${imageId}`
        ),
        'update': (imageId,information) => axios.put(
            `/nova-vendor/media-library/update-media/${imageId}`,{
                information
            }
        )
        
    }
}