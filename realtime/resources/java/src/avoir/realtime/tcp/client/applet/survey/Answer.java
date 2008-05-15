/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.client.applet.survey;

/**
 *
 * @author developer
 */
public class Answer {

    private int yes;
    private int no;

    public Answer(int yes, int no) {
        this.yes = yes;
        this.no = no;
    }

    public int getNo() {
        return no;
    }

    public void setNo(int no) {
        this.no = no;
    }

    public int getYes() {
        return yes;
    }

    public void setYes(int yes) {
        this.yes = yes;
    }
}
