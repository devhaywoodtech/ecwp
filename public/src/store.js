import { configureStore } from '@reduxjs/toolkit';
import datesReducer from './reducer/admin';

export default configureStore({
    reducer: {
        dates : datesReducer,
    },
    //Added to remove the "A non-serializable value was detected in the state, in the path"
    middleware: (getDefaultMiddleware) =>  getDefaultMiddleware({
      serializableCheck: false,
    }),
});
