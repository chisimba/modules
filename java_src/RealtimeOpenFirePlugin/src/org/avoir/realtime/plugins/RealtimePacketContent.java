/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.avoir.realtime.plugins;

/**
 *utility class for creating realtime content
 * @author david
 */
public class RealtimePacketContent {

  private StringBuilder sb = new StringBuilder();

  public void addTag(String tag, Object value) {
      sb.append("<" + tag + ">").append(value).append("</" + tag + ">");
  }

  @Override
  public String toString() {
      return sb.toString();
  }
}
