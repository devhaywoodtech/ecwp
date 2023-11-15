import { createTheme } from '@mui/material/styles';

export default createTheme({
    palette: {
        type: 'light',
        primary: {
            main: '#080708',
        },
        secondary: {
            main: '#0051A8',
        },
        background: {
            default: '#FFFFFF',
            paper: '#fdfdff',
        },
    },
    components: {
        MuiCssBaseline : {
            styleOverrides: {
                body: {
                  scrollbarColor: "#0051A8 #fefefe",
                  "&::-webkit-scrollbar, & *::-webkit-scrollbar": {
                    backgroundColor: "#fefefe",
                  },
                  "&::-webkit-scrollbar-thumb, & *::-webkit-scrollbar-thumb": {
                    borderRadius: 20,
                    backgroundColor: "#0051A8",
                    minHeight: 24,
                    border: "5px solid #fefefe",
                  },
                  "&::-webkit-scrollbar-thumb:focus, & *::-webkit-scrollbar-thumb:focus": {
                    backgroundColor: "#0051A8",
                  },
                  "&::-webkit-scrollbar-thumb:active, & *::-webkit-scrollbar-thumb:active": {
                    backgroundColor: "#0051A8",
                  },
                  "&::-webkit-scrollbar-thumb:hover, & *::-webkit-scrollbar-thumb:hover": {
                    backgroundColor: "#0051A8",
                  },
                  "&::-webkit-scrollbar-corner, & *::-webkit-scrollbar-corner": {
                    backgroundColor: "#fefefe",
                  },
                },
              },
        },
        MuiOutlinedInput: {
            styleOverrides: {
                root: {
                    'fieldset':{
                        border: 0,
                    },
                    'input': {
                        boxShadow: 'rgba(0, 0, 0, 0.16) 0px 1px 4px',
                        paddingLeft : 10,
                        paddingRight : 10,
                        paddingTop : 3,
                        paddingBottom : 3,
                        border: 0,
                        borderRadius : 3,
                    },
                    "input:hover": {
                        boxShadow: 'rgba(0, 0, 0, 0.16) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px',
                        outline: 0,
                        border:0,
                        color:'#080708'
                    },               
                },
            }
        },
       
        
    },
    typography: {
        fontFamily: 'Poppins',
        fontWeightLight: 300,
        fontWeightRegular: 400,
        fontWeightMedium: 600,
        h1: {
            fontFamily: 'Montserrat',
            fontWeight: 600,
        },
        h2: {
            fontFamily: 'Montserrat',
            fontWeight: 600,
        },
        h3: {
            fontFamily: 'Montserrat',
            fontWeight: 600,
        },
        h4: {
            fontFamily: 'Montserrat',
            fontWeight: 600,
        },
        h5: {
            fontFamily: 'Montserrat',
            fontWeight: 600,
        },
        button: {
            fontWeight: 600,
        },
    },
    shadows: [
        'rgba(0, 0, 0, 0.16) 0px 1px 2px 0px',
        'rgba(0, 0, 0, 0.16) 0px 1px 2px 0px',
        'rgba(0, 0, 0, 0.16) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;',
        'rgba(0, 0, 0, 0.16) 0px 1px 4px',
        'rgba(99, 99, 99, 0.2) 0px 2px 8px 0px',
        'rgba(99, 99, 99, 0.2) 0px 2px 5px 0px',
        'rgba(55, 114, 255, 0.2) 2px 2px 5px 2px', //6- Blue Shade
        'rgba(230, 57, 70, 0.2) 0px 2px 5px 0px', // 7- Pink Shade
        'rgba(55, 114, 255, 0.2) 0px 2px 5px 0px', // 8- Pink Shade
        'rgba(55, 114, 255, 0.2) 0px 2px 5px 0px', // 9- Pink Shade
        'rgba(55, 114, 255, 0.2) 0px 2px 5px 0px', // 10- Pink Shade
        'rgba(55, 114, 255, 0.2) 0px 2px 5px 0px', // 11- Pink Shade
        'rgba(55, 114, 255, 0.2) 0px 2px 5px 0px', // 12- Pink Shade
        'rgba(55, 114, 255, 0.2) 0px 2px 5px 0px', // 13- Pink Shade
        'rgba(55, 114, 255, 0.2) 0px 2px 5px 0px', // 14- Pink Shade
        'rgba(55, 114, 255, 0.2) 0px 2px 5px 0px', // 15- Pink Shade
        'rgba(55, 114, 255, 0.2) 0px 2px 5px 0px', // 16- Pink Shade
        'rgba(55, 114, 255, 0.2) 0px 2px 5px 0px', // 17- Pink Shade
        'rgba(55, 114, 255, 0.2) 0px 2px 5px 0px', // 18- Pink Shade
        'rgba(55, 114, 255, 0.2) 0px 2px 5px 0px', // 19- Pink Shade
        'rgba(55, 114, 255, 0.2) 0px 2px 5px 0px', // 20- Pink Shade
        'rgba(55, 114, 255, 0.2) 0px 2px 5px 0px', // 21- Pink Shade
        'rgba(55, 114, 255, 0.2) 0px 2px 5px 0px', // 22- Pink Shade
        'rgba(55, 114, 255, 0.2) 0px 2px 5px 0px', // 23- Pink Shade
        'rgba(55, 114, 255, 0.2) 0px 2px 5px 0px', // 24- Pink Shade
    ],
});