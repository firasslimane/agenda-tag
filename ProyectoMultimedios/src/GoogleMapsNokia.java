
import com.jappit.midmaps.googlemaps.GoogleMaps;
import com.jappit.midmaps.googlemaps.GoogleMapsCoordinates;
import com.jappit.midmaps.googlemaps.GoogleStaticMap;
import com.jappit.midmaps.googlemaps.GoogleStaticMapHandler;
import com.jappit.midmaps.googlemaps.GoogleMapsMarker;

import javax.microedition.lcdui.Canvas;
import javax.microedition.lcdui.Command;
import javax.microedition.lcdui.CommandListener;
import javax.microedition.lcdui.Display;
import javax.microedition.lcdui.Displayable;
import javax.microedition.lcdui.Form;
import javax.microedition.lcdui.Graphics;
import javax.microedition.lcdui.List;
import javax.microedition.lcdui.TextField;
import javax.microedition.lcdui.game.GameCanvas;
import javax.microedition.location.Criteria;
import javax.microedition.location.Location;
import javax.microedition.location.LocationException;
import javax.microedition.location.LocationProvider;
import javax.microedition.location.QualifiedCoordinates;


/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Administrador
 */
public class GoogleMapsNokia extends Canvas implements GoogleStaticMapHandler,CommandListener{

    int ancho = this.getWidth();
    int alto = this.getHeight();

    double latitud = -33.063056;
    double longitud = -71.639444;
    int zoom = 15;
    double movimiento = 0.001;
    QualifiedCoordinates lastCoord;

    GoogleMaps gMap = null;
    GoogleStaticMap mapa = null;
    GoogleMapsMarker marker = null;

    Command volver,aceptar,zoomIn,zoomOut;
    List atras;
    Form pedirDatos;
    Command guardarDatos,cancelar;
    Display d;
    String datosGuardar;

    TextField mensaje,identificador;

    public GoogleMapsNokia(List atras,Display d){
        this.d = d;
        this.atras = atras;
        //todo lo referente a obtener localidad mediante gps
        /*
        try {
            Criteria criteria = new Criteria();
            criteria.setCostAllowed(false);     //no usar proveedor de pago
            criteria.setAddressInfoRequired(true);
            criteria.setSpeedAndCourseRequired(true);
            LocationProvider provider = LocationProvider.getInstance(criteria);
            if(provider != null){
                try{
                    Location location = provider.getLocation(-1);
                    lastCoord = location.getQualifiedCoordinates();
                    latitud = lastCoord.getLatitude();
                    longitud = lastCoord.getLongitude();
                    System.out.println(this.lastCoord.getLatitude()+"::"+this.lastCoord.getLongitude());
                }
                catch(java.lang.InterruptedException ex){

                }
            }
        } catch (LocationException ex) {
        }
        */

        //formulario para agregar mensaje
        pedirDatos = new Form("Mensaje");
        guardarDatos = new Command("Guardar",Command.OK,1);
        cancelar = new Command("Cancelar",Command.CANCEL,1);
        pedirDatos.addCommand(guardarDatos);
        pedirDatos.addCommand(cancelar);
        pedirDatos.setCommandListener(this);
        mensaje = new TextField("Mensaje:","",50,TextField.ANY);
        identificador = new TextField("Id:","",20,TextField.ANY);
        pedirDatos.append(mensaje);
        pedirDatos.append(identificador);

        gMap = new GoogleMaps("ABQIAAAAa5KxvO5yy3cIHY2INIzoIRT2yXp_ZAY8_ufC3CFXhHIE1NvwkxSCtaUJyL6If4_Rh6VODSBsCqvv2w");
        marker = new GoogleMapsMarker(new GoogleMapsCoordinates(latitud,longitud));
        marker.setColor(GoogleStaticMap.COLOR_BLUE);
        marker.setColor(GoogleMapsMarker.SIZE_TINY);

        mapa = gMap.createMap(ancho,alto,GoogleStaticMap.FORMAT_PNG);
        mapa.setHandler(this);
        mapa.setCenter(new GoogleMapsCoordinates(latitud,longitud));
	mapa.setZoom(zoom);
        mapa.addMarker(marker);
	mapa.update();
        aceptar = new Command("Aceptar",Command.OK,1);
        volver  = new Command("Cancelar",Command.BACK,1);
        zoomIn = new Command("Zoom In",Command.SCREEN,1);
        zoomOut = new Command("Zoom Out",Command.SCREEN,1);
        this.addCommand(aceptar);
        this.addCommand(zoomIn);
        this.addCommand(zoomOut);
        this.addCommand(volver);
        this.setCommandListener(this);
        //mapa.draw(g, 0, 0, Graphics.TOP | Graphics.LEFT);
    }

    public void GoogleStaticMapUpdated(GoogleStaticMap gsm) {
        repaint();
    }

    protected void keyPressed(int key)
        {
        
	int gameAction = getGameAction(key);
        if(gameAction == Canvas.UP){
            latitud += movimiento;
        }
        if(gameAction == Canvas.DOWN){
            latitud -= movimiento;
        }
        if(gameAction == Canvas.LEFT){
            longitud -= movimiento;
        }
        if(gameAction == Canvas.RIGHT){
            longitud += movimiento;
        }
        mapa.setCenter(new GoogleMapsCoordinates(latitud,longitud));
        mapa.removeMarker(marker);
        marker = new GoogleMapsMarker(new GoogleMapsCoordinates(latitud,longitud));
        marker.setColor(GoogleStaticMap.COLOR_BLUE);
        marker.setColor(GoogleMapsMarker.SIZE_TINY);
        mapa.addMarker(marker);
        System.out.println(latitud+"::"+longitud);
        mapa.update();
	/*
        if(gameAction == Canvas.UP || gameAction == Canvas.RIGHT || gameAction == Canvas.DOWN || gameAction == Canvas.LEFT)
    	{
    		mapa.move(gameAction);
    	}
        */
    }

    public void GoogleStaticMapUpdateError(GoogleStaticMap gsm, int i, String string) {
        repaint();
    }

    public void commandAction(Command c, Displayable d) {
        if(c.equals(volver)){
            System.out.println("Apretó atras");
            this.d.setCurrent(atras);
        }
        if(c.equals(zoomOut)){
            zoom -= 1;
            movimiento *= 2;
            mapa.setZoom(zoom);
            mapa.update();
        }
        if(c.equals(zoomIn)){
            zoom += 1;
            movimiento /= 2;
            mapa.setZoom(zoom);
            mapa.update();
        }
        if(c.equals(aceptar)){
            //pasar a un nuevo form para pedir mensaje.
            System.out.println("Apreto aceptar");
            datosGuardar = latitud+":"+longitud;
            this.d.setCurrent(pedirDatos);
        }
        if(c.equals(guardarDatos)){
            System.out.println(this.identificador.getString());
            System.out.println(this.mensaje.getString());
            //datosGuardar = datosGuardar+":"+
        }
        if(c.equals(cancelar)){}
    }

    protected void paint(Graphics g) {
        mapa.draw(g, 0, 0, Graphics.TOP | Graphics.LEFT);
    }
}