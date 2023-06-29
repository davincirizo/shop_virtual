import { DataGrid } from '@mui/x-data-grid';
import { useState,useEffect } from "react";
import axios from "axios";
import ResponsiveAppBar from '../NavBar/NavBar';





export default function DataTable() {

    const [category,setCategory] = useState([])

    const endpoint = 'http://127.0.0.1:8000/api/categories'

    const getData = async () =>{
    await axios.get(endpoint).then((response)=>{
        const data = response.data
        console.log(data)
        setCategory(data)
    })
    }

    useEffect( ()=>{
    getData()
    },[])


    const columns = [
        { field: 'id', headerName: 'ID', width: 70 },
        { field: 'name', headerName: 'Description', width: 250 },
      ];
      

  return (
    <>
    <ResponsiveAppBar/>

    
    <div style={{ height: 700, width: '100%' }}>
      <DataGrid
        rows={category}
        columns={columns}
        initialState={{
          pagination: {
            paginationModel: { page: 0, pageSize: 10 },
          },
        }}
        pageSizeOptions={[5, 10]}
        checkboxSelection
      />
    </div>
    </>
  );
}