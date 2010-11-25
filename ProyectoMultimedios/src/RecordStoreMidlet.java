
import javax.microedition.rms.RecordStore;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Administrador
 */
public class RecordStoreMidlet {

    RecordStore rs = null;
    static final String ID_RECORDATORIO = "rec",ID_USUARIO = "usr";

    RecordStoreMidlet(){

    }

    public boolean openRecStore(String idTabla){
        try{
            rs.openRecordStore(idTabla, true);
            return true;
        }
        catch(Exception e){
            return false;
        }
    }

    public void closeRecStore()
  {
    try
    {
      rs.closeRecordStore();
    }
    catch (Exception e){}
  }

    public void writeRecord(String dato){
        byte[] rec = dato.getBytes();
        try{
            rs.addRecord(rec,0,rec.length); //grabamos el string como byte
        }
        catch(Exception e){}
    }

    public String[] readRecords(){
        String[] datos = null;
        try{
            byte[] recData = new byte[5]; //es chico para que se agrande despues
            int len,i;
            int numRec = rs.getNumRecords();
            for(i=1;i<=numRec;i++){
                if(rs.getRecordSize(i) > recData.length)    //agrandamos el arreglo de bytes
                    recData = new byte[rs.getRecordSize(i)];
                len = rs.getRecord(i,recData,numRec);
                System.out.println("Record #" + i + ": " + new String(recData, 0, len));
            }

        }
        catch(Exception e){
        }
        return datos;
    }

}
