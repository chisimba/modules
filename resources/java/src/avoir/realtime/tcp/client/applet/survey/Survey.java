/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package avoir.realtime.tcp.client.applet.survey;
import java.util.Vector;
/**
 *
 * @author developer
 */
public class Survey implements java.io.Serializable{
private Vector questions;
private String title;

    public Survey(Vector questions, String title) {
        this.questions = questions;
        this.title = title;
    }

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }



    public Vector getQuestions() {
        return questions;
    }

    public void setQuestions(Vector questions) {
        this.questions = questions;
    }

}
