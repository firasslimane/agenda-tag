/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

import javax.microedition.lcdui.Command;
import javax.microedition.lcdui.CommandListener;
import javax.microedition.lcdui.Display;
import javax.microedition.lcdui.Displayable;
import javax.microedition.lcdui.Form;
import javax.microedition.lcdui.List;
import javax.microedition.midlet.*;
import javax.microedition.rms.RecordStore;

/**
 * @author Administrador
 */
public class Midlet extends MIDlet implements CommandListener{


    String id = "local";
    Display display;
    //Displayables
    List vistaInicial,vistaRec,vistaPref;
    //el gameCanvas que contiene el mapa de google
    GoogleMapsNokia gmap;
    Form login;     //por ahora sera "offline"

    //Atributos de displayables agrupados por profundidad
    String[] lista1 = {"Recordatorios","Preferencias"};

    String[] lista2 = {"Guardar recordatorio","Ver recordatorios"};
    String[] lista3 = {"Nueva preferencia","Eliminar preferencia"};

    String[] lista4 = {"Eliminar","Ver mapa"};

    //Comandos y elementos
    Command okVistaInicial,salir,okRecordatorios,volverRecordatorios,volverPreferencias,okPreferencias;

    public void startApp() {

        //abrimos los recordstore solo para usarlos

        //primera vista
        vistaInicial = new List("Agenda Tag",List.IMPLICIT,lista1,null);   //se crea y agrega elementos
        okVistaInicial = new Command("Ok",Command.OK,1);                    //se crean los comandos
        salir = new Command("Salir",Command.EXIT,1);
        vistaInicial.addCommand(salir);
        vistaInicial.addCommand(okVistaInicial);                            //se agregan los comandos
        vistaInicial.setCommandListener(this);                              //se agrega Listener

        //vista recordatorios
        vistaRec = new List("Recordatorios",List.IMPLICIT,lista2,null);
        okRecordatorios = new Command("Ok",Command.OK,1);
        volverRecordatorios = new Command("Volver",Command.BACK,1);
        vistaRec.addCommand(volverRecordatorios);
        vistaRec.addCommand(okRecordatorios);
        vistaRec.setCommandListener(this);

        //vista preferencias
        vistaPref = new List("Preferencias",List.IMPLICIT,lista3,null);
        okPreferencias = new Command("Ok",Command.OK,1);
        volverPreferencias = new Command("Volver",Command.BACK,1);
        vistaPref.addCommand(volverPreferencias);
        vistaPref.addCommand(okPreferencias);
        vistaPref.setCommandListener(this);
              
        //Se selecciona la primera vista
        display = Display.getDisplay(this);
        //vista mapa
        gmap = new GoogleMapsNokia(vistaRec,display); //se le pasa el formulario que lo precede y el display
        display.setCurrent(vistaInicial);
    }

    public void pauseApp() {
    }

    public void destroyApp(boolean unconditional) {
        notifyDestroyed();
    }

    public void commandAction(Command c, Displayable d) {
        if(c.equals(salir)){
            destroyApp(true);
        }
        if(c.equals(okVistaInicial)){
            if(vistaInicial.getSelectedIndex() == 0){
                display.setCurrent(vistaRec);
            }
            if(vistaInicial.getSelectedIndex() == 1){
                display.setCurrent(vistaPref);
            }
        }
        if(c.equals(okRecordatorios)){
            //depende de la acción
            if(vistaPref.getSelectedIndex() == 0){
                display.setCurrent(gmap);
            }
            if(vistaPref.getSelectedIndex() == 1){}
        }
        if(c.equals(volverRecordatorios)){
            display.setCurrent(vistaInicial);
        }
        if(c.equals(okPreferencias)){
            
        }
        if(c.equals(volverPreferencias)){
            display.setCurrent(vistaInicial);
        }        
    }
}
